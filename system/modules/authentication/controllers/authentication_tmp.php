<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	

/**
 *  Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Authentication_tmp extends MX_Controller {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('authentication/auth');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		/*$data = array();
		
		// Add some required data
		$data['username']   = 'boris';
		$data['login_key']  = $this->auth->generate_salt();
		$data['password']   = '';
		$data['password']   = Auth::encrypt_string($data['password'], $data['login_key']);
		$data['ip_address'] = $this->input->ip_address();
		$data['level']      = 999;
		
		dump($data);
		
		// Add some required data
		$data['username']   = 'sven';
		$data['login_key']  = $this->vault->generate_salt();
		$data['password']   = 'spe5de3dod';
		$data['password']   = $this->vault->encrypt_string($data['password'], $data['login_key']);
		$data['ip_address'] = $this->input->ip_address();
		$data['level']      = 999;
		
		dump($data);*/
		
	} // index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end 


/* End of file .php */