<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Google Analytics Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Ga extends MX_Controller {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function index()
	{
		$this->view = false;
		echo 'GA.';
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end 


/* End of file .php */