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
		$this->load->library('form_validation', array('CI' => $this));
		$this->load->library('mailing/mailer');
		
		// Facebook
		$this->benchmark->mark('Facebook_connect_start');
		$this->_facebook_connect();
		$this->benchmark->mark('Facebook_connect_end');
		
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
		
		if ($this->form_validation->run())
		{
			if (Auth::log_in($this->input->post('email'), $this->input->post('password')))
			{
				redirect(make_http(site_url()));
			}
			else {
				$this->form_validation->set_error('Kriva zaporka.');
			}
		}
		
	} //end login()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function logout()
	{
		Auth::log_out();
		redirect();
		
	} // end logout()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	
	
	
	/* !------------------------------------------------------------------------------------------ */
	/* !✰ Facebook */
	/* !------------------------------------------------------------------------------------------ */
	
	
	/**
	 *
	 */
	private function _facebook_connect()
	{
		$this->load->model('fb/facebook_m');
		
		//dump( $this->facebook_m->get_user() );
		
		
		/*$this->config->load('fb/facebook');
		
		$this->load->library('fb/facebook', array(
			'appId'  => config_item('facebook_app_id'),
			'secret' => config_item('facebook_api_secret'),
			'cookie' => true,
		));
		$this->load->helper('fb/facebook');
		
		$session = $this->facebook->getSession();

		$me = null;
		
		try {
		    $uid = $this->facebook->getUser();
		    $me = $this->facebook->api('/me');
		  } catch (FacebookApiException $e) {
		    error_log($e);
		  }
		
		/*dump($uid);
		dump($me);*/
		
	} // end facebook_connect()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Actions


/* End of file actions.php */