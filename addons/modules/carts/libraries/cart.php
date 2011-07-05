<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Cart Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.2
 */

class Cart extends CMS {
	
	private $_sess_cart_id = 'activecartid';
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Load resources
		$this->config->load('carts/carts');
		$this->config->load('payment/payment');
		$this->load->model('carts/cart_m');
		$this->load->model('carts/cart_item_m');
		$this->load->model('carts/discount_code_m');
		$this->load->library('encrypt');
		
		// Setup encryption
		$this->encrypt->set_cipher(MCRYPT_BLOWFISH);
		$this->encrypt->set_mode(MCRYPT_MODE_CFB);
	
	} // __construct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function id()
	{
		return $this->session->userdata($this->_sess_cart_id);
		
	} // id()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Returns cart for active user
	 *
	 */
	public function get()
	{
		if ($this->id() and $active_cart = $this->cache->get('cart_'.$this->id()))
		{
			return $active_cart;
		}
		else
		{
			$cart = $this->create();
			$this->session->set_userdata($this->_sess_cart_id, $cart->id);
			if ($cart->id) $this->cache->save('cart_'.$cart->id, $cart, 60);
			
			return $cart;
		}
		
	} // get()

	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Returns number of items in cart
	 *
	 */
	public function get_total_items()
	{
		$item_num 	= (int) $this->cart_item_m->count_by('cart_id', $this->id());
		
		return $item_num;
		
	} // get_total_items()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function get_items()
	{
		if ($active_cart_items = $this->cache->get('cart_items_'.$this->id()))
		{
			return $active_cart_items;
		}
		else
		{
			$active_cart_items = $this->cart_item_m->order_by('entry_id')->get_many_by('cart_id', $this->id());
			
			$this->cache->save('cart_items_'.$this->id(), $active_cart_items, 60*10); // 10 minutes
			
			return $active_cart_items;
		}
		
	} // get_items()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Create new cart and add ID into session
	 *
	 * If $overwrite is true a new cart will be created also if a cart alredy exists
	 *
	 */
	public function create($overwrite = false)
	{
		// Check if a cart is alredy in the user session
		$active_cart_id = $this->id();
		$active_cart 	= $this->cart_m->get($active_cart_id);
		
		// Create a cart id it does not exist
		if ( ! $active_cart or $overwrite === true)
		{
			$db_data = array
			(
				'user_id'    => user_id(),
				'status'     => 'pending',
				'ip_address' => $this->input->ip_address(),
			);
			
			// Insert cart, create tracking ID and secret key
			$cart_id 	= $this->cart_m->insert($db_data);
			$track_id 	= $this->create_tracking_id($cart_id, $this->config->item('cart_trackid_prefix'));
			
			// Create secret key
			$secret_data = array
			(
				'user_id'  => user_id(),
				'track_id' => $track_id,
				'cart_id'  => $cart_id,
			);
			$secret_key = base64_encode($this->encrypt->encode(user_id().'_'.$track_id.'_'.$cart_id.'_'.now(), $this->config->item('payment_encrypt_key')));
			
			// Update cart with track ID and secret key
			$this->cart_m->update($cart_id, array
			(
				'track_id'   => $track_id,
				'secret_key' => $secret_key,
			));
			
			// Add to session
			$this->session->set_userdata($this->_sess_cart_id, $cart_id);
			
			// Get the complete cart
			$active_cart = $this->cart_m->get($cart_id);
			
			// Save to cache
			$this->cache->save('cart_'.$cart_id, $active_cart, 60*10); // 10 minutes
		}
		
		// Else just return the ID
		elseif ($active_cart)
		{
			$cart_id  = $active_cart->id;
			$track_id = $active_cart->track_id;
		}
		
		return $active_cart;
		
	} // create()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function add_item($entry_id = null, $increment = true, $data = null)
	{
		// Create a cart if none is present
		$cart_id = $this->id();
		if ( ! $cart_id)
		{
			$cart_id = $this->create();
		}
		
		// Check for item in cart
		$in_cart = $this->cart_item_m->count_by(array
		(
			'cart_id'  => $cart_id,
			'user_id'  => user_id(),
			'entry_id' => $entry_id,
		));
		
		if ( ! $in_cart or $increment)
		{
			$db_data = array(
				'entry_id'        => (int) $entry_id,
				'cart_id'         => $cart_id,
				'user_id'         => user_id(),
				'amount'          => (float) $data['amount'],
				'amount_original' => (float) $data['amount_original'],
				'status'          => 'pending',
				'coupon_sent'     => 0,
			);
			
			// Insert cart item and create tracking ID
			$cart_item_id  = $this->cart_item_m->insert($db_data);
			$item_track_id = $this->create_tracking_id($cart_item_id, 'item');
			
			// Update cart item with track ID
			$this->cart_item_m->update($cart_item_id, array('track_id'=>$item_track_id));
			
			// Update the total amount in cart
			$this->update_cart_total();
			
			// And we need to update the cache
			$this->update_cache();
			
			return $cart_item_id;
		}
		
	} // add_item()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function update_cart_total()
	{
		$cart    = $this->get();
		
		if ($cart) {
			$total      = 0;
			$cart_items = $this->cart_item_m->get_many_by('cart_id', $cart->id);
			
			// We need a loop because some items can have a quantity
			foreach ($cart_items as $item)
			{
				$total += $item->amount;
			}
			
			// Update cart amount
			$this->cart_m->update($cart->id, array('amount' => $total));
			
			return $total;
			
		}
		
		return 0;
		
	} // update_cart_total()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function create_tracking_id($id = null, $type = 'cart')
	{
		if ($id)
		{
			$id  = (int) $id + (int) $this->config->item('cart_trackid_start_from');
			
			if ($type == 'cart') $track_id = $this->config->item('cart_trackid_prefix').sprintf("%09d",$id);
			else                 $track_id = $this->config->item('cart_item_trackid_prefix').sprintf("%09d",$id);
			
			return $track_id;
		}
		
		return null;
		
	} // create_tracking_id()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function organize_items_by_product($items = null)
	{
		$organized_items = array();
		
		if ($items) {
			foreach ($items as $key=>$item) {
				// Check if already in list
				$in_list = false; $in_list_key = false;
				foreach ($organized_items as $tmp_key=>$tmp_item) :
					if ($item->entry_id == $tmp_item->entry_id) :
						$in_list = true;
						$in_list_key = $tmp_key;
						break;
					endif;
				endforeach;
				
				// Add new item to list or update quantity
				if ($in_list === true && $in_list_key !== false) :
					$organized_items[$in_list_key]->quantity++;
					//echo 'UPDATE<br />';
				else :
					$item->quantity = 1;
					$organized_items[] = $item;
					//echo 'NEW<br />';
				endif;
				//echo '--------------<br /><br />';
			} // end foreach
		} // end if
		
		return $organized_items;
		
	} // organize_items_by_product()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function update_cache()
	{
		@$this->cache->delete('cart_'.$this->id());
		@$this->cache->delete('cart_items_'.$this->id());
		CMS::$front_data->cart       = $this->get();
		CMS::$front_data->cart_items = $this->get_items();
		
	} // update_cache()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function remove_from_session()
	{
		@$this->cache->delete('cart_'.$this->id());
		@$this->cache->delete('cart_items_'.$this->id());
		$this->session->unset_userdata($this->_sess_cart_id);
		
	} // end remove_from_session()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function encrypt_shop_secret_key()
	{
		
	} // end encrypt_shop_secret_key()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function decrypt_shop_secret_key($key = null)
	{
		if ($key) {
			$secret_key = str_replace(array('_', '-', ':', '%'), array('=', '+', '/', "\\"), $key);
			
			return $this->encrypt->decode(base64_decode($secret_key), $this->config->item('payment_encrypt_key'));
		
		} // end if 
		
		return null;
		
	} // end decrypt_shop_secret_key()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Cart


/* End of file cart.php */