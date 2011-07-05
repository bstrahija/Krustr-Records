<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Users Admin Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2009, Boris Strahija, Creo
 * @version 	0.2
 */

class Users_admin extends Backend {
	
	private $_user_roles = array();
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Call the MY_Controller constructor
		parent::__construct();
		
		// Restricted access
		Auth::restrict('admin');
		
		// Set navigation mark
		$this->set_nav_mark('system');
		$this->set_nav_mark('users', 2);
		
		// Load resources
		$this->load->model('users/user_m');
		$this->load->model('users/user_meta_m');
		
		// User roles
		$me               = get_user();
		$roles            = $this->config->item('user_groups', 'auth');
		Backend::$data->user_roles = array();
		foreach ($roles as $role=>$level) {
			if ($me->level >= $level) $this->_user_roles[$level] = ucfirst($role);
			Backend::$data->user_roles[$level] = ucfirst($role);
		} // end foreach
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		admin_redirect('users/all');
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function all()
	{
		// Title and buttons
		$this->set_title('Users');
		
		
		// Filter limit
		if ( ! (int) $this->input->get('filter_limit')) $this->db->limit(20);
		else                                            $this->db->limit((int) $this->input->get('filter_limit'));
		
		// Filter keywords
		if ((string) trim($this->input->get('filter_keywords'))) {
			$this->db->like('username', (string) trim($this->input->get('filter_keywords')));
		} // end if
		
		// Filter dates
		if ((int) $this->input->get('filter_before')) {
			$this->db->where('u.created_at <=', (int) $this->input->get('filter_before'), false);
		} // end if
		if ((int) $this->input->get('filter_after')) {
			$this->db->where('u.created_at >=', (int) $this->input->get('filter_after'), false);
		} // end if

		// Filter levels
		if ((int) trim($this->input->get('filter_level'))) {
			$this->db->where('level', (int) trim($this->input->get('filter_level')));
		} // end if
		
		
		// Get all users
		$users = Backend::$data->users = $this->user_m->get_all_extended();
		
		// Ajax request means no layout
		if ($this->input->is_ajax_request()) {
			$this->layout = false;
		} // end if
		
	} // end all()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function add()
	{
		// Title and buttons
		$this->set_title('Add New User');
		
		// Create the form
		$form = new Form();
		$form->open()
		     ->fieldset('User')
		     	->text('username', 'Username', 'required|trim|min_length[4]|unique[users.username]',  null, 'autocomplete="off",autofocus')
		     	->text('email',    'Email',    'required|trim|valid_email|unique[users.email]',       null, 'autocomplete="off"')
		     	->select('level', $this->_user_roles, 'Role')
		     	->password('password', 'Password', 'required|min_length[8]', null, 'autocomplete="off"')
		     ->fieldset('Personal data')
		     	->text('first_name',  'First Name')
		     	->text('last_name',   'Last Name')
		     	->textarea('address', 'Adress')
		     	->text('postal_code', 'Postal Code')
		     	->text('city',        'City')
		     	->text('country',     'Country')
		     	->text('phone',       'Phone')
		     ->html('<p class="btns">')
		     ->submit('Save')
		     ->html('</p>')
		     ;
		
		// Form to view
		Backend::$data->form   = $form->get();
		Backend::$data->errors = $form->errors;
		
		// Run actions if valid
		if ($form->valid) {
			// Prepare data and register user
			$data = elements(array('username', 'email', 'password', 'level'), $this->input->post());
			$data['level'] = $data['level'][0];
			$id = Auth::register(null, null, false, false);
			
			// Activate
			$user = Auth::get_user($id);
			Auth::activate($id, $user->activation_key, false);
			
			// Set level
			$this->user_m->update($id, array('level'=>$data['level'], 'created_at'=>now(), 'updated_at'=>now()));
			
			// Notice and redirect
			Notice::add('User "'.$user->username.'" saved.');
			admin_redirect('users/edit/'.$id);
			
		} // end if
		
	} // end add()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function me()
	{
		admin_redirect('users/edit/'.user_id());
		
	} // end me()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function edit($id = null)
	{
		// Get the user, but without the password
		$user = get_user($id); unset($user->password);
		
		// Title and buttons
		$this->set_title('Edit user <i>"'.$user->username.'"</i>');
		
		// Create the form
		$form = new Form();
		$form->open()
		     ->fieldset('User')
		     	->text('username', 'Username', 'required|trim|min_length[4]|unique_except[users.username;'.$id.']',  $user->username, 'autocomplete="off",autofocus')
		     	->text('email',    'Email',    'required|trim|valid_email|unique_except[users.email;'.$id.']',       $user->email,    'autocomplete="off"')
		     	->select('level', $this->_user_roles, 'Role', $user->level)
		     ->fieldset('Personal data')
		     	->text('first_name',   'First Name',   null, $user->first_name)
		     	->text('last_name',    'Last Name',    null, $user->last_name)
		     	->text('display_name', 'Display Name', null, $user->display_name)
		     	->textarea('address',  'Adress',       null, $user->address)
		     	->text('postal_code',  'Postal Code',  null, $user->postal_code)
		     	->text('city',         'City',         null, $user->city)
		     	->text('country',      'Country',      null, $user->country)
		     	->text('phone',        'Phone',        null, $user->phone)
		     ->fieldset('Change password')
		     	->password('password', 'New Password', 'min_length[8]', null, 'autocomplete="off"')
		     ->html('<p class="btns">')
		     ->submit('Save')
		     ->html('</p>')
		     ;
		
		// Form to view
		Backend::$data->form   = $form->get();
		Backend::$data->errors = $form->errors;
		
		// Run actions if valid
		if ($form->valid) {
			$this->user_m->update($id, array(
				 'username'		=> $this->input->post('username')
				,'email'		=> $this->input->post('email')
			));
			
			$this->user_meta_m->update_by(array('user_id'=>$id), array(
				 'first_name'    => $this->input->post('first_name')
				,'last_name'     => $this->input->post('last_name')
				,'display_name'  => $this->input->post('display_name')
				,'address'       => $this->input->post('address')
				,'postal_code'   => $this->input->post('postal_code')
				,'city'          => $this->input->post('city')
				,'country'       => $this->input->post('country')
				,'phone'         => $this->input->post('phone')
			), false);
			
			// Notice and redirect
			Notice::add('User "'.$user->username.'" updated.');
			admin_redirect('users/edit/'.$id);
			
		} // end if
		
	} // end edit()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Changes the user status to trashed
	 *
	 */
	public function trash($id = null)
	{
		$this->view = false;
		
		if ($id) {
			//$this->user_m->update($id, array('status'=>'trashed'));
			
		} // end if
		
	} // end trash()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Permanently deletes the user
	 *
	 */
	public function delete($id = null)
	{
		$this->view = false;
		
		if ($id) {
			//$this->user_m->delete($id);
			
		} // end if
		
	} // end delete()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function login_as($id)
	{
		// Set user restriction
		Auth::restrict('superadmin');
		$this->view = FALSE;
		
		// Get user
		$user = $this->user_m->get($id);
		
		// Logout login
		Auth::log_out();
		Auth::log_in($user->email, $user->password, false, true);
		
		//redirect();
		
	} // end login_as()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Users_admin


/* End of file users_admin.php */