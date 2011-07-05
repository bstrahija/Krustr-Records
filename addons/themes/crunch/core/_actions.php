<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Theme Actions
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Theme_actions extends KR_Actions {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Load resources
		$this->load->library('form_validation');
		
		// Now lets route the actions
		if ($this->input->post('action') == "login") :
			$this->login();
			
		elseif ($this->input->post('action') == "register") :
			$this->register();
		
		elseif ($this->uri->segment(1) == "logout" || $this->uri->segment(2) == "logout") :
			$this->logout();
		
		endif;
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function login()
	{
		// Set validation rules
		$this->form_validation->set_rules('email', 		'Email', 	'xss_clean|trim|required|valid_email');
		$this->form_validation->set_rules('password', 	'Password', 'xss_clean|trim|required');
		
		if ($this->form_validation->run()) {
			if ($this->vault->log_in($this->input->post('email'), $this->input->post('password'))) {
				redirect(make_http(site_url()));
			}
			else {
				$this->form_validation->set_error('Kriva zaporka.');
				
			} // end if
		} // end if
		
	} //end login()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function logout()
	{
		$this->vault->log_out();
		redirect();
		
	} // end logout()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Actions


/* End of file actions.php */