<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Orders Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Orders_admin extends Backend {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		
		// Set navigation mark
		$this->set_nav_mark('orders');
		$this->set_nav_mark('orders', 2);
		
		// Load resources
		$this->load->model('carts/cart_m');
		$this->load->model('carts/cart_item_m');
		$this->load->model('content/entry_m');
		$this->load->library('carts/cart');
		$this->load->library('deals/deal');
		
		// Restricted access
		Auth::restrict('admin');
		
		// Prepare forms
		$this->_set_form_fields();
		
	} // __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		admin_redirect('orders/all');
		
	} // index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function all($deal_id = null)
	{
		// Get all deals
		$deals = Backend::$data->deals = $this->entry_m->order_by('published_at', 'DESC')->get_many_by(array
		(
			'status'          => 'published',
			'published_at <=' => time(),
			'channel'         => 'deals',
		));
		
		// If no deal ID is set, redirect to last deal
		//if ( ! $deal_id) admin_redirect('orders/index/'.$this->data['deals'][0]->id);
		
		// Get all sold items
		$this->db->select('cart_items.*, users.username, user_meta.first_name, user_meta.last_name, users.email, entries.title as deal_title
							,carts.user_id, carts.track_id AS cart_track_id, users.id AS user_id, carts.payment_type, carts.discount_code, carts.discount
							,carts.updated_at AS cart_updated_at, carts.created_at AS cart_created_at, carts.purchased_at')
		          ->from('cart_items');
		
		// Get asociated cart, user and deal
		$this->db->join('carts', 		'carts.id = cart_items.cart_id', "left");
		$this->db->join('users', 		'carts.user_id = users.id', "left");
		$this->db->join('user_meta', 	'user_meta.user_id = users.id', "left");
		$this->db->join('entries', 		'cart_items.entry_id = entries.id', "left");
		
		
		// For 1 deal
		if ($deal_id)
		{
			$this->db->where('cart_items.entry_id', $deal_id);
			$this->db->limit(99999);
		}
		elseif ((int) $this->input->get('filter_entry'))
		{
			$this->db->where('cart_items.entry_id', (int) $this->input->get('filter_entry'));
			$this->db->limit(99999);
		}
		else
		{
			// Filter limit
			if ( ! (int) $this->input->get('filter_limit')) $this->db->limit(20);
			else                                            $this->db->limit((int) $this->input->get('filter_limit'));
		}
		
		
		// Filter status
		if ((string) $this->input->get('filter_status'))
		{
			if ($this->input->get('filter_status') != 'all')
			{
				$this->db->where('cart_items.status', $this->input->get('filter_status'));
			}
		}
		else
		{
			$this->db->where('cart_items.status', 'pg-success');
		}
		
		
		// Filter payment type
		if ((string) $this->input->get('filter_payment'))
		{
			if ($this->input->get('filter_payment') != 'all')
			{
				$this->db->where('carts.payment_type', $this->input->get('filter_payment'));
			}
		}
		
		
		// Filter keywords
		if ((string) trim($this->input->get('filter_keywords')))
		{
			$this->db->like('username',                  (string) trim($this->input->get('filter_keywords')));
			$this->db->or_like('user_meta.display_name', (string) trim($this->input->get('filter_keywords')));
			$this->db->or_like('users.email',            (string) trim($this->input->get('filter_keywords')));
			$this->db->or_like('entries.title',          (string) trim($this->input->get('filter_keywords')));
		}
		
		// Filter dates
		if ((int) $this->input->get('filter_before'))
		{
			$this->db->where('u.created_at <=', (int) $this->input->get('filter_before'), false);
		}
		
		if ((int) $this->input->get('filter_after'))
		{
			$this->db->where('u.created_at >=', (int) $this->input->get('filter_after'), false);
		}
		
		
		// Order
		$this->db->order_by('carts.id', 'DESC');
		
		// Result
		Backend::$data->orders  = $this->db->get()->result();
		Backend::$data->deal_id = $deal_id;
		
		// Ajax request means no layout
		if ($this->input->is_ajax_request())
		{
			$this->layout = false;
		}
		
	} // all()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function add()
	{
		// Get all deals
		$deal_select = array();
		$deals = $this->entry_m->order_by('published_at', 'DESC')->get_many_by(array
		(
			'channel' => 'deals',
			'status'  => 'published',
		));
		
		foreach ($deals as $deal)
		{
			$deal_select[$deal->id] = '['.$deal->id.'] '.$deal->title;
		}
		
		$coupon_number = array(1=>1,2,3,4,5,6,7,8,9,10);
		
		// Build form
		$form = new Form();
		$form->open(current_url(), 'content-form', 'class="content-form"')
		     ->fieldset('Pick a deal, enter number of coupons, and enter the email address of the user')
		     ->select('deal_id', $deal_select, 'Pick a deal')
	         ->select('coupon_number', $coupon_number, 'Number of coupons', 1)
		     ->text('user_email', 'User Email', 'required|trim|valid_email')
		     ->html('<p class="btns">')->submit('Add order')->html('</p>');
		Backend::$data->form = $form->get();

		
		// Run validation and actions
		if ($form->valid)
		{
			// Get resources
			$deal_id      = $this->input->post('deal_id'); $deal_id = $deal_id[0];
			$user         = $this->user_m->get_by('email', $this->input->post('user_email'));
			$deal         = $this->entry_m->get_extended($deal_id);
			$deal->fields = $this->entry_m->fields($deal);
			//$deal = $this->_extra_entry_data($deal);
			//$deal->fields = $deal_fields = $this->fields($deal);
			
			if ($user)
			{
				$dbdata_cart = array(
					'user_id'      => $user->id,
					'payment_type' => 'bank',
					'status'       => 'pg-success',
					'created_at'   => time(),
					'updated_at'   => time(),
					'purchased_at' => time(),
				);
				
				$cart_id                 = $this->cart_m->insert($dbdata_cart);
				$cart_track_id           = $this->cart->create_tracking_id($cart_id, $this->config->item('cart_trackid_prefix'));
				$dbdata_cart['id']       = $cart_id;
				$dbdata_cart['track_id'] = $cart_track_id;
				$this->cart_m->update($cart_id, array('track_id'=>$cart_track_id));

				$coupon_number = $this->input->post('coupon_number'); $coupon_number = (int) $coupon_number[0];
				if ( ! $coupon_number) $coupon_number = 1;
				
				$total_original_amount 	= 0;
				$total_coupon_amount 	= 0;
				
				for ($i = 0; $i < $coupon_number; $i++)
				{
					$dbdata_item = array(
						'entry_id'        => $deal_id,
						'cart_id'         => $cart_id,
						'user_id'         => $user->id,
						'coupon_code'     => $this->coupon->generate_code(),
						'coupon_sent'     => 0,
						'amount'          => (float) $deal->fields['coupon_value'],
						'amount_original' => (float) $deal->fields['original_value'],
						'status'          => 'pg-success',
						'created_at'      => time(),
						'updated_at'      => time(),
					);
					$cart_item_id  = $this->cart_item_m->insert($dbdata_item);
					$item_track_id = $this->cart->create_tracking_id($cart_item_id, $this->config->item('cart_item_trackid_prefix'));
					$this->cart_item_m->update($cart_item_id, array('track_id'=>$item_track_id));
					
					$total_original_amount += (float) $deal->fields['original_value'];
					$total_coupon_amount   += (float) $deal->fields['coupon_value'];
					
				}
				
				// Update cart amount
				$this->cart_m->update($cart_id, array('amount'=>$total_coupon_amount));
				
				// Check is deal is successful and update the cart items
				$this->coupon->update_all_codes($deal->id);
				
				// And try to send the coupons
				$this->coupon->send_all($deal->id);
				
			}
			
			Notice::add('Order saved.');
			
			// Redirect
			admin_redirect('orders/add');
			die();
			
		}
		else
		{
			Backend::$data->errors = $form->errors;
		}
		
	} // add()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function delete($id = null)
	{
		$this->view = false;
		
		// Get cart item
		$cart_item = $this->cart_item_m->get($id);
		
		// Get cart
		$cart = $this->cart_m->get($cart_item->cart_id);
		
		// Get all items in cart
		$cart_items = $this->cart_item_m->get_many_by('cart_id', $cart->id);
		
		// Trash the item
		$this->cart_item_m->update($id, array('status'=>'trashed'));
		
		// A notice
		Notice::add('Item trashed.');
			
		if ( ! $this->input->is_ajax_request())
		{
			// Redirect
			admin_redirect('orders/index/'.$cart_item->entry_id);
		}
		
	} // delete()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function restore($id = null)
	{
		$this->view = false;
		
		// Get item
		$item = $this->cart_item_m->get($id);
		
		// Update status to draft
		$this->cart_item_m->update($id, array('status'=>'pg-success'));
		
		// Notice and redirect
		Notice::add('Status of coupon changed to <strong>"PG Success"</strong>.');
		admin_redirect('orders/index/'.$item->entry_id);
		
	} // restore()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function send_coupon($id = null, $resend = false)
	{
		$this->view = false;
		
		if ($id)
		{
			$item = $this->cart_item_m->get($id);
			
			// Get deals
			$deal = $this->entry_m->get_extended_with_fields($item->entry_id);
			$deal = $this->deal->prepare($deal);
			
			// Send it
			if ( ! $item->coupon_sent or $resend) {
				$this->coupon->send($item, $deal, true);
			}
			
			Notice::add('The coupon has been sent.');
		}
		
		admin_redirect('orders/index/'.$deal->id);
		
	} // send_coupon()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function resend_coupon($id = null)
	{
		$this->view = false;
		
		$this->send_coupon($id, true);
		
	} // resend_coupon()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function info($item_id = null)
	{
		// Set special layout
		$this->layout = 'layouts/pop';
		
		// Get cart item
		$cart_item = $this->cart_item_m->get($item_id);
		
		// Generate new code if doesn't exist
		if ($cart_item and $cart_item->status == 'pg-success' and ! $cart_item->coupon_code) :
			$coupon_code = $this->deal->generate_coupon_code();
			$cart_item->coupon_code = $coupon_code;
			$this->cart_item_m->update($item_id, array('coupon_code'=>$coupon_code));
		endif;
		
		// Get cart
		$cart = $this->cart_m->get($cart_item->cart_id);
		
		// Get deal
		$deal = $this->entry_m->get($cart_item->entry_id);
		
		// Get user
		$user = get_user($cart->user_id);
		
		Backend::$data->cart_item = $cart_item;
		Backend::$data->cart      = $cart;
		Backend::$data->user      = $user;
		Backend::$data->deal      = $deal;
		
	} // info()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function client_list($deal_id = null)
	{
		// Title and buttons
		$this->set_title('Orders');
		$this->set_nav_mark('client_list', 2);
		
		// Get all deals
		$deals = $this->entry_m->order_by('published_at', 'DESC')->get_many_by(array
		(
			 'channel'	=>'deals'
			,'status'	=>'published'
		));
		Backend::$data->deals = $deals;
		
		
		// Through GET?
		if ( ! $deal_id and $this->input->get('filter_entry')) $deal_id = (int) $this->input->get('filter_entry');
		
		
		// Get the orders
		if ($deal_id)
		{
			Backend::$data->deal = $this->entry_m->get($deal_id);
			
			// Get all sold items
			$this->db->select('cart_items.*, users.username, user_meta.first_name, user_meta.last_name, users.email, entries.title as deal_title
								,carts.user_id, users.id AS user_id')
						//->from('cart_items')->where('cart_items.status', 'pg-success')->order_by('cart_items.cart_id', 'DESC');
						->from('cart_items')->where('cart_items.status', 'pg-success')->order_by('user_meta.first_name', 'ASC')->order_by('user_meta.last_name', 'ASC');
			
			// Get asociated cart, user and deal
			$this->db->join('carts',     'cart_items.cart_id = carts.id');
			$this->db->join('users',     'carts.user_id = users.id');
			$this->db->join('user_meta', 'user_meta.user_id = users.id');
			$this->db->join('entries',   'cart_items.entry_id = entries.id');
			
			// For 1 deal
			if ($deal_id) :
				$this->db->where('cart_items.entry_id', $deal_id);
			endif;
			
			// Result
			Backend::$data->orders = $this->db->get()->result();
			Backend::$data->deal_id = $deal_id;
		}
		
		// Run validation and actions
		if ($this->input->post('deal_id'))
		{
			$deal_id = $this->input->post('deal_id');
			admin_redirect('orders/client_list/'.$deal_id[0]);
		}
		
		// Ajax request means no layout
		if ($this->input->is_ajax_request())
		{
			$this->layout = false;
		}
		
	} // client_list()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function discounts()
	{
		// Title and buttons
		$this->set_title('Orders');
		$this->set_nav_mark('codes', 2);
		
		// Select
		$this->db->select('dc.code, dc.used_by_id, dc.id, dc.discount, dc.discount_type, dc.slots, 
		                   dc.used, dc.used_data, dc.expires_at, dc.created_at, dc.updated_at,
		                   u.id AS user_id, u.email, um.first_name, um.last_name, um.display_name
		                   ')
	             ->from('discount_codes AS dc')
	             ->join('users AS u',      'u.id = dc.used_by_id',       'left')
	             ->join('user_meta AS um', 'um.user_id = dc.used_by_id', 'left');
		
		
		// Filter limit
		if ( ! (int) $this->input->get('filter_limit')) $this->db->limit(500);
		else                                            $this->db->limit((int) $this->input->get('filter_limit'));
		
		
		// Filter status
		if ((string) $this->input->get('filter_status'))
		{
			if ($this->input->get('filter_status') == 'used')
			{
				$this->db->where('used', 1);
			}
			elseif ($this->input->get('filter_status') == 'free')
			{
				$this->db->where('used', 0);
			}
		}
		
		
		// Filter keywords
		if ((string) trim($this->input->get('filter_keywords')))
		{
			$this->db->like('code', (string) trim($this->input->get('filter_keywords')));
		}
		
		
		// Get all codes
		Backend::$data->codes = $this->db->get()->result();;
		
	} // end codes()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function code_generator($num = 1500)
	{
		$all_codes = array();
			
		while (count($all_codes) < $num) :
			$new_code = random_str(10, 10);
			$in_db = $this->discount_code_m->get_by('code', $new_code);
			
			if ( ! in_array($new_code, $all_codes) and ! $in_db) :
				$all_codes[] = $new_code;
			endif;
		endwhile;
		
		/*echo '<pre>';
		print_r($all_codes);
		echo '</pre>';*/
		
		foreach ($all_codes as $code) :
			echo $code, "<br />\n";
			/*$this->discount_code_m->insert(array(
				 'code'=>$code
				,'discount'=>10
				,'discount_type'=>'percent'
				,'slots'=>1
				,'used'=>0
				,'expires_at'=> mktime(23,59,29,3,31,2011)
			));*/
		endforeach;
		
		$this->view = false;
		
	} // end ()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _set_form_fields()
	{
		
	} // end _set_form_fields()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Orders_admin


/* End of file orders_admin.php */