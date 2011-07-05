<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Payment Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Payment extends CMS {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load resources
		$this->config->load('payment');
		$this->load->library('option');
		$this->load->library('curl');
		$this->load->library('carts/cart');
		$this->load->library('deals/deal');
		$this->load->library('encrypt');
		
		// Setup encryption
		//$this->encrypt->set_cipher(MCRYPT_BLOWFISH);
		//$this->encrypt->set_mode(MCRYPT_MODE_CFB);
		
	} // __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		exit('No direct script access allowed.');
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function init()
	{
		// Restricted access
		Auth::restrict('member');
		
		// Get the cart and update the total just in case
		$this->cart->update_cart_total();
		$cart = $this->cart->get();
		
		if ($cart) {
			$cart_items = $this->cart->get_items();
			
			if ($cart_items and $cart->amount > 0) {
				// Prepare data for view
				$data = array(
					 'cart'			=> $cart
					,'cart_items'	=> $cart_items
				);
				
				// Load the view
				$this->load->view('init', $data);
				
			}
			else {
				echo "Cart is empty.";
				
			} // end if
		}
		else {
			echo "No cart present.";
			
		} // end if
		
	} // end init()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function response()
	{
		$redirect_to = 'payment/error';
		
		$track_id 		= $this->uri->segment(3);
		$secret_key 	= $this->cart->decrypt_shop_secret_key($this->uri->segment(4));
		$secret_data 	= explode('_', $secret_key);
		
		/*echo '<pre>'; print_r($track_id); echo '</pre>';
		echo '<pre>'; print_r($secret_key); echo '</pre>';
		echo '<pre>'; print_r($secret_data); echo '</pre>';
		dump(count($secret_data));
		echo $track_id,'<br>';
		echo $secret_data[1];
		die();*/
		
		// There need to be 4 items in decoded data and compare track ID's
		if (count($secret_data) == 4 and $track_id == $secret_data[1]) {
			$secret_user_id 	= $secret_data[0];
			$secret_track_id 	= $secret_data[1];
			$secret_cart_id 	= $secret_data[2];
			
			// Now find the cart
			$cart = $this->cart_m->get($secret_cart_id);
			
			// Perform actions
			if ($cart) {
				// Get cart items
				$cart_items = $this->cart_item_m->get_many_by('cart_id', $secret_cart_id);
				
				// Update cart object
				$db_data = array(
					 'status'			=> 'pg-success'
					,'payment_type' 	=> 'cc'
					,'transaction_id' 	=> $_GET['tid']
					,'pg_return'		=> json_encode($_GET)
					,'purchased_at'		=> time()
				);
				
				// Update discount code if exists
				if ($cart->discount_code)
				{
					$discount_code = $this->discount_code_m->get_by('code', $cart->discount_code);
					
					if ($discount_code and ! $discount_code->slots)            $discount_code = null;
					if ($discount_code and $discount_code->expires_at < now()) $discount_code = null;
					
					if ($discount_code and $discount_code->slots)
					{
						$slots = $discount_code->slots - 1;
						$this->discount_code_m->update($discount_code->id, array('slots'=>$slots, 'used_by_id'=>$cart->user_id));
						
						// Update the cart also
						$db_data['discount'] 	= $discount_code->discount;
						$db_data['amount'] 		= (float) $cart->amount - round((float) $cart->amount * ($discount_code->discount / 100), 2);
					}
					else
					{
						// Update the cart also
						$db_data['discount'] 	= NULL;
						$db_data['amount'] 		= NULL;
					}
				}
				
				// Update cart items
				foreach ($cart_items as $item)
				{
					$this->cart_item_m->update($item->id, array('status'=>'pg-success'));
					
					if ( ! @$item->coupon_code)
					{
						$check_deal = $this->deal->get($item->entry_id);
						$check_deal = $this->deal->prepare($check_deal);
						
						if ($this->deal->is_successful($check_deal))
						{
							$this->coupon->update_code($item);
						}
						
						//$this->cart_item_m->update($item->id, array('coupon_code'=>$this->deal->generate_coupon_code()));
					}
				}
				
				// Run cart update
				$this->cart_m->update($secret_cart_id, $db_data);
				$cart = $this->cart_m->get($cart->id);
				
				// Send email
				$this->_send_email_confimation($cart);
				
				// Send coupons
				$deal_ids = $this->deal->get_deal_ids_from_cart($cart);
				$this->coupon->send_all($deal_ids);
				
				// Remove from session
				$this->cart->remove_from_session();
				
				// Redirect to success page
				$redirect_to = 'payment/success/'.$secret_track_id;
				
			} // end if
		
		}
		else {
			// Now find the cart
			$cart = $this->cart_m->get_by('track_id', $track_id);
			
			// Perform actions
			if ($cart) {
				// Update cart object
				$db_data = array(
					 'status'			=> 'pg-error'
					,'payment_type' 	=> 'cc'
					,'transaction_id' 	=> @$_GET['tid']
					,'pg_return'		=> json_encode($_GET)
				);
				
				// Run cart update
				$this->cart_m->update($cart->id, $db_data);
				
				// Get cart items
				$cart_items = $this->cart_item_m->get_many_by('cart_id', $cart->id);
				
				// Update cart items
				if ($cart_items) {
					foreach ($cart_items as $item) {
						$this->cart_item_m->update($item->id, array('status'=>'pg-error'));
					} // end foreach
				} // end if
				
			} // end if
			
			redirect('payment/error');
		
		} // end if
		
		redirect($redirect_to);
		
	} // end response()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _send_email_confimation($cart = null)
	{
		if ($this->config->item('payment_send_confirmation')) {
			// Get user
			$user 		= get_user();
			$subject 	= 'Mudra kupovina - rezervacija kupona';
			$pg_data 	= json_decode($cart->pg_return);
			
			if ($pg_data->card == 'AMEX') 	$credit_card_name = 'American Express';
			else 							$credit_card_name = $pg_data->card;
			
			// Prepare message
			$data = array(
				 'title'				=> $subject
				,'credit_card_name'		=> $credit_card_name
				,'pg_data'				=> $pg_data
				,'cart'					=> $cart
			);
			$message 	= $this->parser->parse('_email/confirmation', $data, true);
			
			// Email
			$config['charset'] = 'utf-8';
			$config['mailtype'] = 'html';
			$this->load->library('email');
			$this->email->initialize($config);
			$this->email->from('info@mudrakupovina.hr', 'Mudra kupovina');
			$this->email->to($user->email); 
			$this->email->bcc('debug@creolab.hr'); 
			$this->email->subject($subject);
			$this->email->message($message);
			$this->email->send();
		} // end if
		
	} // end _send_email_confimation()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function success($track_id = null)
	{
		$data = array(
			'redirect_url' => site_url('uspjesna-rezervacija/'.$track_id)
		);
		
		$this->load->view('success', $data);
		
	} // end success()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function error()
	{
		$data = array(
			'redirect_url' => site_url('greska-kod-rezervacije')
		);
		
		$this->load->view('success', $data);
		
	} // end error()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function system_error()
	{
		echo 'Error in payment gateway.';
		echo '<pre>';
		print_r($_GET);
		echo '</pre>';
		echo '<pre>';
		print_r($_POST);
		echo '</pre>';
		echo '<pre>';
		print_r($_COOKIE);
		echo '</pre>';
		
	} // end system_error()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Payment


/* End of file payment.php */