<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Coupon Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2009, Boris Strahija, Creo
 * @version 	0.2
 */

class Coupon extends CMS {
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Load some resources
		//$this->load->library('deals/deal');
		$this->load->library('parser');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Return all coupons for a user
	 * The $organize parameter will organize the coupons by status
	 *
	 */
	public function all_for_user($user_id = null, $organize = true)
	{
		if ( ! $user_id) $user_id = user_id();
		
		if ($user_id)
		{
			// Get the coupons
			$coupons = $this->cart_item_m->order_by('updated_at', 'DESC')->cart_item_m->get_many_by(array(
				'user_id' => $user_id,
				'status'  => 'pg-success',
			));
			
			// Organize coupons into categories
			if ($coupons)
			{
				$active 	= array();
				$expired 	= array();
				$pending 	= array();
				
				foreach ($coupons as $coupon)
				{
					// Prepare deal data
					$deal = $this->deal->get($coupon->entry_id);
					
					if ( ! $coupon->coupon_code)
					{
						$coupon->coupon_code = $this->update_code($coupon);
					}
					
					// Only if deal exists (some people could be stupid and delete some deals)
					if ($deal)
					{
						$deal         = $this->deal->prepare($deal);
						$coupon->deal = $deal;
						
						// Get cart
						$coupon->cart = $this->cart_m->get($coupon->cart_id);
						
						if ($deal->coupon_expires < time())        $expired[] = $coupon;
						elseif ($this->deal->is_successful($deal)) $active[]  = $coupon;
						else                                       $pending[] = $coupon;
					}
				}
				
				// Insert coupons to array
				$coupons = array(
					 array(
						 'name'		=> 'pending'
						,'coupons'	=> $pending
					)
					,array(
						 'name'		=> 'active'
						,'coupons'	=> $active
					)
					,array(
						 'name'		=> 'expired'
						,'coupons'	=> $expired
					)
				);
			}
			
			return $coupons;
		}
		
		return null;
		
	} // end all_for_user()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Adds a code to the coupon if it's missing
	 *
	 */
	public function update_code($coupon = null)
	{
		if ($coupon) {
			$deal = $this->deal->get($coupon->entry_id);
			$deal = $this->deal->prepare($deal);
			
			if ($this->deal->is_successful($deal) and ! $coupon->coupon_code) {
				$coupon_code = $this->generate_code();
				$this->cart_item_m->update($coupon->id, array('coupon_code'=>$coupon_code));
				$coupon->coupon_code = $coupon_code;
				
				return $coupon_code;
			} // end if
			
			if ($coupon->coupon_code) return $coupon->coupon_code;
			
		} // end if
		
		return null;
		
	} // end update_code()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Adds a code to all the coupon if it's missing, but for a specific deal
	 *
	 */
	public function update_all_codes($deal_id = null)
	{
		if ($deal_id)
		{
			$deal = $this->deal->get($deal_id);
			
			if ($deal)
			{
				$deal = $this->deal->prepare($deal);
				
				// Get all coupons
				$cart_items = $this->cart_item_m->get_many_by(array(
					'entry_id' => $deal->id,
					'status'   => 'pg-success',
				));
				
				foreach ($cart_items as $item)
				{
					$this->update_code($item);
				}
			}
		}
		
		return false;
		
	} // update_all_codes()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Generates a unique code for the coupon
	 *
	 */
	function generate_code()
	{
		$code = strtoupper(random_string('alnum', 12));
		
		// See if it's unique
		$coupon = $this->cart_item_m->get_by('coupon_code', $code);
		if ($coupon) $code = $this->generate_code();
		
		return $code;
		
	} // end generate_code()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Send a specific coupon to a user
	 *
	 */
	public function send($coupon = null, $deal = null, $send_again = false)
	{
		if ($coupon)
		{
			if ( ! $deal)
			{
				$deal = $this->deal->get($coupon->entry_id);
				$deal = $this->deal->prepare($deal);
			}
			
			// Email
			$config['charset']  = 'utf-8';
			$config['mailtype'] = 'html';
			$this->load->library('email');
			$this->email->initialize($config);
			
			// Only send if not already sent, or override in effect
			if ($send_again or ! $coupon->coupon_sent)
			{
				$user        = get_user($coupon->user_id);
				$coupon_code = $this->update_code($coupon);
				
				// Prepare message
				$data    = $this->generate_data($coupon, $deal);
				$subject = 'Vaš kupon';
				$message = $this->parser->parse('../../addons/mail/coupon', $data, true);
				
				// Send the coupon
				$this->email->clear();
				$this->email->from('info@mudrakupovina.hr', 'Mudra kupovina');
				
				if ($coupon->gift) $this->email->to($coupon->gift_to_email);
				else               $this->email->to($user->email);
				
				$this->email->bcc('debug@creolab.hr'); 
				$this->email->subject($subject);
				$this->email->message($message);
				
				if ($this->email->send())
				{
					// Update coupon
					$this->cart_item_m->update($coupon->id, array('coupon_sent'=>1));
				}
			}
		}
		
	} // end send()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Send all the coupons for a specific deal to the users
	 * -----------------------------------------------------
	 * If $send_again is set to true coupons will be sent no matter if they were already sent
	 * $send_again_all will send coupons to all users that bough coupon for those deals
	 * 
	 */
	public function send_all($deal_ids = null, $send_again = false, $send_again_all = false)
	{
		if ($deal_ids)
		{
			if ( ! is_array($deal_ids)) $deal_ids = array($deal_ids);
			
			// Get the deals
			foreach ($deal_ids as $did)
			{
				$deal = $this->deal->get($did);
				$deal = $this->deal->prepare($deal);
				
				// Now get all coupons
				$coupons = $this->cart_item_m->get_many_by(array
				(
					'entry_id' => $deal->id,
					'status'   => 'pg-success',
					
				));
				
				// Prepare coupons and send 'em
				if ($coupons and $this->deal->is_successful($deal))
				{
					foreach ($coupons as $coupon)
					{
						// Send it
						$this->send($coupon, $deal);
					}
				}
			}
		}
		
	} // end send_all()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Generate data that is passed to the coupon view (for display or mail)
	 */
	function generate_data($coupon = null, $deal = null)
	{
		if ($coupon and $deal)
		{
			// Data container for coupon
			$data           = array();
			$data['deal']   = $deal;
			$data['coupon'] = $coupon;
			
			// User and company
			$user     = get_user($coupon->user_id);
			$relation = $this->entry_relation_m->get_by('entry_id', $deal->id);
			
			if ($relation)
			{
				$company = $this->entry_m->get_extended($relation->related_id);
				
				if ($company)
				{
					$company->fields = $this->entry_m->fields($company);
					if ($company and is_array($company)) $company = $company[0];
					$data['company'] = $company;
				}
			}
			else
			{
				$data['company'] = new stdClass();
			}
			
			// Now get more coupons bought by this user for this deal
			$data['related_coupons'] = $related_coupons = $this->cart_item_m->order_by('id', 'ASC')->get_many_by(array
			(
				'status'   => 'pg-success',
				'entry_id' => $deal->id,
				'user_id'  => $coupon->user_id,
			));
			
			// And then get the row number of current coupon
			if (count($related_coupons) > 1)
			{
				foreach ($related_coupons as $k=>$cp)
				{
					if ($coupon->id == $cp->id) $coupon_number = (int) $k + 1;
				}
				
				$data['coupon_number'] = $coupon_number = $coupon_number.'/'.count($related_coupons);
			}
			
			// Is gift?
			$data['is_gift'] = $is_gift = $coupon->gift;
			
			// Prepare the data
			$data['coupon_code']  = $coupon->coupon_code;
			$data['track_id']     = $coupon->track_id;
			$data['deal_title']   = $deal->title;
			$data['coupon_value'] = $coupon->amount_original;
			$data['coupon_price'] = $coupon->amount;
			
			// Title
			if ($is_gift) $data['title'] = @$deal->fields['gift-title'];
			else          $data['title'] = $deal->title;
			
			// Subline
			if ( ! $is_gift and @$deal->fields['subline']) $data['subline'] = @$deal->fields['subline'];
			
			// Gift message
			if ($is_gift)
			{
				$gift_message = $data['gift_message'] = $coupon->gift_message;
			}
			
			// Expiration date
			$data['coupon_expires'] = date('d.m.Y.', $deal->coupon_expires);
			
			// Buyers name
			$data['buyer_name'] = $user->display_name;
		
			// Get carrier name
			if ($is_gift) $data['carrier_name'] = $coupon->gift_to_name;
			else          $data['carrier_name'] = $data['buyer_name'];
			
			return $data;
		}
		
		return null;
		
	} // end generate_data()
	
	
	/* ------------------------------------------------------------------------------------------ */
	

} //end Deal


/* End of file coupon.php */