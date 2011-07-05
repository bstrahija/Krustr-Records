<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Deal Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2009, Boris Strahija, Creo
 * @version 	0.2
 */

class Deal extends CMS {
	
	private $_deal_cache_ttl = 900; // 15 minutes
	
	// Some setup categories
	public $main_deal_category      = 1; // Glavna ponuda
	public $parent_city_category    = 2; // Parent category for all cities
	public $main_city_category      = 3; // Zagreb
	public $main_city_category_slug = 'zagreb'; // Zagreb
	public $deal_table              = 'ch_hr_deals';
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Load some resources
		$this->config->load('deals/deals');
		$this->load->model('content/entry_m');
		$this->load->model('content/entry_relation_m');
		$this->load->model('content/entry_category_m');
		$this->load->model('categories/category_m');
		$this->load->model('carts/cart_m');
		$this->load->model('carts/cart_item_m');
		$this->load->library('deals/coupon');
		$this->load->library('content/publisher');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Return a specific deal by ID
	 *
	 */
	public function get($id = null)
	{
		if ($deal = $this->cache->get('deal_'.$id) and isset($deal->time_left))
		{
			$deal = $this->prepare($deal);
			return $deal;
		}
		else
		{
			// Get main deal
			$deal = $this->publisher->get($id);
			
			if($deal)
			{
				$deal = $this->prepare($deal);
				@$this->cache->delete('deal_'.$id);
				$this->cache->save('deal_'.$id, $deal, $this->_deal_cache_ttl);
				
				return $deal;
			}
		}
		
		return null;
			
	} // end get()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Get the main deal for a specific category
	 *
	 */
	public function get_main($city_id = null)
	{
		// Setup deal cache slug
		if ($city_id) $deal_cache_slug = 'main_deal_'.$city_id;
		else          $deal_cache_slug = 'main_deal';
		
		// Clean cache for testing
		//@$this->cache->delete($deal_cache_slug);
		
		if ($main_deal = $this->cache->get($deal_cache_slug) and isset($main_deal->time_left))
		{
			$main_deal = $this->prepare($main_deal);
			return $main_deal;
		}
		else
		{
			// Get main deal
			if ($city_id) $main_deal = $this->get_by_category(array($this->main_deal_category, (int) $city_id), true);
			else          $main_deal = $this->get_by_category(array($this->main_deal_category, $this->main_city_category), true);
			
			if ($main_deal)
			{
				// And add additional deal data
				$main_deal = $this->prepare($main_deal);
				
				@$this->cache->delete($deal_cache_slug);
				$this->cache->save($deal_cache_slug, $main_deal, $this->_deal_cache_ttl);
				
				return $main_deal;
			}
		}
		
		return null;
			
	} // end get_main()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_giveaway($id = null)
	{
		// Setup deal cache slug
		$cache_slug = 'giveaway';
		
		// Clean cache for testing
		//@$this->cache->delete($cache_slug);
		
		if ($giveaway = $this->cache->get($cache_slug) and isset($giveaway->time_left))
		{
			$giveaway = $this->prepare($giveaway);
			return $giveaway;
		}
		else
		{
			$giveaway = $this->publisher->get(61);
			
			if ($giveaway)
			{
				// And add additional deal data
				$giveaway = $this->prepare($giveaway);
				
				@$this->cache->delete($cache_slug);
				$this->cache->save($cache_slug, $giveaway, $this->_deal_cache_ttl);
				
				return $giveaway;
			}
		}
		
		return null;
		
	} // get_giveaway()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Return all active deals, category is optional
	 *
	 */
	public function get_all_active()
	{
		// Clean cache for testing
		//@$this->cache->delete('active_deals');
		
		if ($active_deals = $this->cache->get('active_deals'))
		{
			foreach ($active_deals as $key=>$active_deal)
			{
				$active_deals[$key] = $this->prepare($active_deal);
			}
			
			return $active_deals;
		}
		else
		{
			// Get 'em 
			$active_deals = $this->db->from($this->deal_table.' AS e')
			                         ->where('e.f_expires_at > UNIX_TIMESTAMP()')
							         ->where('published_at <=', now())
			                         ->order_by('published_at', 'DESC')
			                         ->get()->result()
			                         ;
			
			// Prepare the data
			if ($active_deals)
			{
				foreach ($active_deals as $active_deal)
				{
					$active_deal = $this->prepare($active_deal);
				}
			}
			
			return $active_deals;
		}
		
		return null;
			
	} // end get_all_active()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Return all expired deals
	 *
	 */
	public function get_all_past()
	{
		// Clean cache for testing
		//@$this->cache->delete('active_deals');
		
		if ($past_deals = $this->cache->get('past_deals'))
		{
			// Prepare the data
			foreach ($past_deals as $key=>$past_deal)
			{
				$past_deals[$key] = $this->prepare($past_deal);
			}
			
			return $past_deals;
		}
		else
		{
			// Get 'em 
			$past_deals = $this->db->from($this->deal_table.' AS e')
			                       ->where('e.f_expires_at < UNIX_TIMESTAMP()')
							       ->where('published_at <=', now())
			                       ->order_by('published_at', 'DESC')
			                       ->get()->result()
			                       ;
			
			// Prepare the data
			if ($past_deals)
			{
				foreach ($past_deals as $key=>$past_deal)
				{
					$past_deals[$key] = $this->prepare($past_deal);
				}
			}
			
			return $past_deals;
		}
		
		return null;
			
	} // end get_all_past()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Get entry in specific category
	 * ---
	 * It is possible to get by many categories, then the $explicit parameter comes to play
	 * If it's set to true, the entry must be in all categories, when it's false it can be in any
	 *
	 */
	function get_by_category($categories = null, $explicit = false, $many = false)
	{
		if ($categories)
		{
			// Prepare categories
			if ( ! is_array($categories)) $categories = array($categories);
			$categories = implode(',', $categories);
			
			// Get the entry
			if ( ! $explicit)
			{
				$this->db->from($this->deal_table.' AS e')
				         ->join('entry_category AS ec', 'ec.entry_id = e.id', 'left')
				         ->where('e.published_at <=', now())
				         ->where_in('ec.category_id', $categories)
				         ->order_by('published_at', 'DESC')
				         ;
				$entry = $this->db->get()->row();
			}
			else
			{
				$this->db->select('e.*, GROUP_CONCAT(ec.category_id ORDER BY category_id ASC) AS entry_categories, COUNT(ec.entry_id) AS category_count')
				         ->from($this->deal_table.' AS e')
				         ->join('entry_category AS ec', 'ec.entry_id = e.id', 'inner')
				         ->where('e.published_at <=', now())
				         ->where_in('ec.category_id', explode(',', $categories))
				         ->group_by('ec.entry_id')
				         ->having("entry_categories = '".$categories."' AND f_expires_at > UNIX_TIMESTAMP()")
				         ->order_by('e.published_at', 'DESC')
				         ;
				if ($many) $entry = $this->db->get()->result();
				else       $entry = $this->db->get()->row();
			}
			
			return $entry;
		}
		
		return null;
		
	} // get_by_category()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function get_many_by_category($categories = null, $explicit = false)
	{
		return $this->get_by_category($categories, $explicit, true);
		
	} // get_many_by_category()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_gallery($deal_id = null)
	{
		if ($deal_id)
		{
			// Get the deal
			$deal = $this->get($deal_id);
			
			// Set cache name
			$cache_name = 'deal_'.$deal_id.'_gallery';
			
			// And try to get the gallery also
			if (isset($deal->f_gallery) and (int) $deal->f_gallery)
			{
				if ($deal_gallery = $this->cache->get($cache_name))
				{
					return $deal_gallery;
				}
				else
				{
					// Get gallery and associated images
					$deal_gallery        = $this->gallery_m->get((int) $deal->f_gallery);
					$deal_gallery_images = $this->gallery_image_m->get_many_by('gallery_id', (int) $deal->f_gallery);
					
					// Prepare data for template and cache it if everything went ok
					if ($deal_gallery and $deal_gallery_images)
					{
						$deal_gallery->images     = $deal_gallery_images;
						@$this->cache->delete($cache_name);
						$this->cache->save($cache_name, $deal_gallery, $this->_deal_cache_ttl);
						
						return $deal_gallery;
					}
				}
			}
		}
		
	} // get_gallery()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function get_company($deal_id = null)
	{
		if ($deal_id)
		{
			$deal = $this->get($deal_id);
			
			if ($deal)
			{
				$company = $this->publisher->get((int) $deal->f_company);
				
				return $company;
			}
		}
		
		return null;
		
	} // get_company()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Return all region specific deal categores (eg. cities)
	 *
	 */
	public function get_region_categories()
	{
		
		return null;
			
	} // end get_region_categories()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Return the ID's of all the deals in a cart
	 *
	 */
	function get_deal_ids_from_cart($cart = null)
	{
		if ($cart)
		{
			$cart_items = $this->cart_item_m->get_many_by('cart_id', $cart->id);
			
			if ($cart_items)
			{
				$deal_ids = array();
				
				foreach ($cart_items as $item)
				{
					if ( ! in_array($item->entry_id, $deal_ids)) $deal_ids[] = (int) $item->entry_id;
				}
				
				return $deal_ids;
			}
		}
		
		return null;
		
	} // end get_deal_ids_from_cart()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Add extra information to the deal object
	 * Some calculation is done (eg. deal expiration, sold coupons etc.)
	 *
	 */
	public function prepare($deal = null)
	{
		if ($deal)
		{
			// Set some coupon values
			$deal->coupon_value 	= (float) @$deal->f_coupon_value;
			$deal->original_value 	= (float) @$deal->f_original_value;
			$deal->saved_value 		= $deal->original_value - $deal->coupon_value;
			$deal->discount 		= round((1 - ($deal->coupon_value / $deal->original_value)) * 100, 0);
			$deal->expires_at 		= (int) $deal->f_expires_at;
			$deal->time_left 		= (int) $deal->expires_at - time();
			$coupon_expires 		= (int) @$deal->f_coupon_expiration_date;
			$deal->coupon_expires 	= mktime(23,59,59,date('m', $coupon_expires),date('d', $coupon_expires),date('Y', $coupon_expires)); // 'Till theend of the day
			
			// Get number of sold items
			$deal->sold_number = (int) $this->cart_item_m->count_by(array(
				 'entry_id'	=> $deal->id
				,'status'	=> 'pg-success'
			));
			
			// There's the possibility to fake the number of sold coupons so we set that here
			if (isset($deal->f_fake_coupons) and (int) $deal->f_fake_coupons)
			{
				$deal->sold_number += (int) $deal->f_fake_coupons;
			}
			
			// Get number of bought items
			$deal->bought_number = (int) $this->cart_item_m->count_by(array(
				 'entry_id'	=> $deal->id
				,'status'	=> 'pg-success'
				,'user_id'	=> user_id()
			));
			
			// Still needed number to sell
			$deal->need_to_sell = (int)  @$deal->f_minimum_coupons - $deal->sold_number;
			
			// Is expired, successful, available
			$deal->is_expired       = (bool) $this->is_expired($deal);
			$deal->is_successful    = (bool) $this->is_successful($deal);
			$deal->is_available     = (bool) $this->is_available($deal);
		}
		
		return $deal;
		
	} // end prepare()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !Helper methods */
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Return number of total sold coupons for a deal
	 * 
	 */
	public function sold($deal = null)
	{
		$sold = 0;
		
		// Find deal by ID
		if (is_numeric($deal))
		{
			$deal = $this->get($deal);
		}
		
		
		// Find number of sold coupons
		if ($deal and isset($deal->sold_number))
		{
			$sold = (int) $deal->sold_number;
		}
		elseif ($deal)
		{
			// Get number of sold items
			$sold = (int) $this->cart_item_m->count_by(array
			(
				'entry_id' => $deal->id,
				'status'   => 'pg-success',
			));
		}
		
		
		// There's the possibility to fake the number of sold coupons so we set that here
		if (isset($deal->f_fake_coupons) and (int) $deal->f_fake_coupons)
		{
			$deal->sold_number += (int) $deal->f_fake_coupons;
		}
		
		return $sold;
		
	} // end sold()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Return total number of all coupons sold
	 *
	 */
	public function total_sold()
	{
		$sold = self::$_ci->cart_item_m->count_by(array('status' => 'pg-success'));
		
		return (int) $sold;
		
	} // end total_sold()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Ammount of money saved on all deals
	 *
	 */
	public function money_saved()
	{
		$total_value = self::$_ci->db->where('status', 'pg-success')->select_sum('amount_original')->get('cart_items')->row();
		$total_value = (float) $total_value->amount_original;
		$total_spent = self::$_ci->db->where('status', 'pg-success')->select_sum('amount')->get('cart_items')->row();
		$total_spent = (float) $total_spent->amount;
		
		// Calculate saved ammount
		$total_saved = $total_value - $total_spent;
		
		return $total_saved;
		
	} // end money_saved()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Number of coupons a user has bought for a specific deal
	 * User ID and deal ID are optional
	 *
	 */
	public function bought_coupons($deal_id = null, $user_id = null)
	{
		$bought = 0;
		
		if ( ! $user_id) $user_id = user_id();
		
		// Get number of bought items
		if ($entry_id AND $user_id)
		{
			$bought = self::$_ci->cart_item_m->count_by(array
			(
				'user_id'  => $user_id,
				'entry_id' => $entry_id,
				'status'   => 'pg-success',
			));
		}
			
		return (int) $bought;
		
	} // end bought_coupon()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Return number of coupons that the user still can buy
	 *
	 */
	public function allowed_coupons($deal = null, $user_id = null)
	{
		$allowed = 0;
		
		if ( ! $user_id) $user_id = user_id();
		
		if ($deal and $user_id)
		{
			// Get deal
			if (is_numeric($deal))
			{
				$deal = $this->get($deal);
			}
			
			$coupons_left 	= (int) $deal->f_maximum_coupons - (int) $this->sold($deal);
			$allowed 		= (int) $deal->f_maximum_coupons_per_user - (int) $this->bought($deal->id, $user_id);
			if ($allowed > $coupons_left) $allowed = $coupons_left;
		}
		
		// If user is not logged in always return total available number
		if ($deal and ! $user_id)
		{
			$coupons_left 	= (int) $deal->f_maximum_coupons - (int) $this->sold($deal);
			return          $coupons_left;
		}
		
		return $allowed;
		
	} // allowed_coupons()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function bought($entry_id = null, $user_id = null)
	{
		$bought = 0;
		
		if ( ! $user_id) $user_id = user_id();
		
		if ($entry_id AND $user_id)
		{
			// Get number of bought items
			$bought = $this->cart_item_m->count_by(array
			(
				'user_id'  => $user_id,
				'entry_id' => $entry_id,
				'status'   => 'pg-success',
			));
		}
			
		return (int) $bought;
		
	} // bought()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Check if deal is available for purchase
	 * Check number of sold coupons, number of bought, expiration time etc.
	 *
	 */
	function is_available($deal = null)
	{
		$available = true;
		
		if ($deal)
		{
			// Get bought and sold numbers
			$bought = $this->bought($deal->id);
			$sold   = $deal->sold_number;
			
			// Based on number of coupons sold
			if ($bought >= $deal->f_maximum_coupons_per_user) $available = false;
			if ($sold   >= $deal->f_maximum_coupons)          $available = false;
			
			// Based on time
			if ($deal->time_left <= 0) $available = false;
		}
		
		return $available;
		
	} // end is_available()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Check if deal has expired
	 *
	 */
	function is_expired($deal = null)
	{
		if ($deal)
		{
			if (is_array($deal)) $deal = (object) $deal;
			
			if ( ! isset($deal->time_left))
			{
				$deal = $this->get($deal->id);
				$deal = $this->prepare($deal);
			}
			
			if ($deal->time_left > 0) return false;
			else                      return true;
		}
		
		return false;
		
	} // end is_expired()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Check if a deal was successful
	 * Additional parameter to consider the time left for the deal
	 *
	 */
	public function is_successful($deal = null, $consider_time = false)
	{
		if ($deal)
		{
			if ($deal->sold_number >= (int) $deal->f_minimum_coupons)
			{
				if ($consider_time and $deal->time_left < 0) return true;
				elseif ( ! $consider_time)                   return true;
			}
		}
		
		return false;
		
	} // end is_successful()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function status_box($deal = null, $template = null)
	{
		$return_str = '';
		
		if ($deal and $template)
		{
			// Get number of sold soupons
			$data['sold_coupons'] = $this->sold($deal);
			if ($data['sold_coupons'] == 1) 	$data['deal_num_message'] = 'Do sada prodan <em>'.$data['sold_coupons'].'</em> kupon';
			else 								$data['deal_num_message'] = 'Do sada prodano <em>'.$data['sold_coupons'].'</em> kupona';
			
			// Number of needed coupons
			if ($deal->need_to_sell == 1) $data['deal_num_need_message'] = 'Treba kupiti još <em>'.$deal->need_to_sell.'</em> kupon';
			else                          $data['deal_num_need_message'] = 'Treba kupiti još <em>'.$deal->need_to_sell.'</em> kupona';
			
			// Prepare the data
			if ($deal->time_left <= 0) { // Expired deal
				$data['deal_status'] 				= 'expired';
				$data['deal_success_message'] 		= 'Ponuda je istekla';
				$data['deal_num_message'] = 'Prodano <em>'.$data['sold_coupons'].'</em> kupona';
				if ($deal->need_to_sell >= 1) { // Deal was not successful
					$data['deal_success_submessage'] 	= 'nije kupljeno dovoljno kupona';
				}
				else {  // Deal was successful
					$data['deal_success_submessage'] 	= 'Sutra budite brži!';
				} // end if
			
			}
			else { // Deals in progress
				$user_can_buy = (int) $this->allowed_coupons($deal);
				
				if ((int) $deal->sold_number >= (int) $deal->f_maximum_coupons) { // All coupons are soldout
					$data['deal_status'] 				= 'soldout';
					$data['deal_success_message'] 		= 'Ponuda je uspjela';
					$data['deal_success_submessage'] 	= 'Svi kuponi su kupljeni.'; //. Sutra budite brži!
				
				}
				elseif ($user_can_buy < 1) { // User can't buy anymore coupons because he bought all he is allowed
					if ($deal->need_to_sell >= 1) {
						$data['deal_status'] 				= 'soldout-user';
						$data['deal_success_message'] 		= 'Ponuda je u tijeku';
						//$data['deal_success_submessage'] 	= 'Već ste kupili maksimalni broj kupona za ovu ponudu. Pozovite prijatelje!';
						$data['deal_success_submessage'] 	= 'Već ste kupili maksimalni broj kupona.';
					}
					else { // Deal was successful
						$data['deal_status'] 				= 'success';
						$data['deal_success_message'] 		= 'Ponuda je uspjela';
						//$data['deal_success_submessage'] 	= 'Već ste kupili maksimalni broj kupona za ovu ponudu. Pozovite prijatelje!';
						$data['deal_success_submessage'] 	= 'Već ste kupili maksimalni broj kupona.';
					} // end if
				
				}
				else {	
					if ($deal->need_to_sell >= 1) { // Deal was not successful, still going on
						$data['deal_status'] 				= 'pending';
						$data['deal_success_message'] 		= 'Ponuda je u tijeku';
						$data['deal_success_submessage'] 	= 'Još '.$deal->need_to_sell.' za prolaz!';
					}
					else { // Deal was successful
						$data['deal_status'] 				= 'success';
						$data['deal_success_message'] 		= 'Ponuda je uspjela';
						$data['deal_success_submessage'] 	= 'Možete i dalje kupovati!';
					} // end if
				
				} // end if
				
			} // end if
			
			$return_str .= $this->load->view('../../'.CMS::$current_theme_path.'/'.$template, $data, true);
			
		} // end if
		
		return $return_str;
		
	}
	
	/* ------------------------------------------------------------------------------------------ */
	

} //end Deal


/* End of file deal.php */