<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Deploy Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Deploy extends MX_Controller {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		$config['mailtype'] = 'html';
		$this->load->library('email', $config);
		
		
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		$message = '<h1>Debug</h1><hr>';
		
		$message .= '<h2>POST</h2><pre><code>'.var_export($_POST, true).'</code></pre><br><br><hr>';
		$message .= '<h2>GET</h2><pre><code>'.var_export($_GET, true).'</code></pre><br><br><hr>';
		$message .= '<h2>COOKIE</h2><pre><code>'.var_export($_COOKIE, true).'</code></pre><br><br><hr>';
		$message .= '<h2>FILES</h2><pre><code>'.var_export($_FILES, true).'</code></pre><br><br><hr>';
		
		
		echo '<br><br>--------<br>';
		$this->email->from('info@mudrakupovina.hr', 'Mudra Kupovina');
		$this->email->to('boris@creolab.hr');
		$this->email->subject('Deployment [Mudra Kupovina] - '.date('Y/m/d H:i:s'));
		$this->email->message($message);
		$this->email->send();
		//echo $this->email->print_debugger();
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end 


/* End of file .php */