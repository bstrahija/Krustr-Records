<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Authentication Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.2
 */

class Authentication_admin extends MY_Controller {
	
	protected static $forms;
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function __construct()
	{
		// Call the MY_Controller constructor
		parent::__construct();
		
		// Set the layout
		$this->layout = '../modules/authentication/views/layout';
		
		// Load resources
		$this->load->library('authentication/auth');
		$this->load->library('assets/assets');
		$this->load->model('users/user_m');
		$this->load->helper('authentication/auth');
		$this->load->library('form');
		
		// Add some assets
		$this->assets->css('login.css');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function index()
	{
		$this->login();
		
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function login()
	{
		$data = new stdClass();
		
		// If already logged in
		if (logged_in())
		{
			admin_redirect('dashboard');
		}
		
		// Create the form
		$form = new Form();
		$form->open()
		     ->fieldset()
		     ->text('identity', 'Username', 'required')
		     ->password('password', 'Password', 'required')
		     	->html('<p class="btns">')
		     	->submit('Login')
		     	->html('</p>')
		     ;
		$data->yield = $form->get();
		
		// Try to login if form is ok
		if ($this->form_validation->run())
		{
			if (Auth::log_in($this->input->post('identity'), $this->input->post('password')))
			{
				$redirect_to = $this->session->userdata('afterlogin');
				
				if ($redirect_to)
				{
					$this->session->unset_userdata('afterlogin');
					redirect($redirect_to);
				}
				else
				{
					admin_redirect('dashboard');
				}
			}
			else
			{
				$this->form_validation->set_error('Wrong credentials.');
			}
		}
		
		// Try to get errors
		$data->errors = validation_errors();
		
		// And load the view
		$this->load->view('layout', $data);
		
	} // login()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function logout()
	{
		$this->view = FALSE;
		Auth::log_out();
		admin_redirect('login');
		
	} //end logout()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function link_with_facebook()
	{
		$this->view = FALSE;
		echo 'This functionality isn\'t available yet.';
		
	} // end link_with_facebook()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Authentication


/* End of file authentication.php */