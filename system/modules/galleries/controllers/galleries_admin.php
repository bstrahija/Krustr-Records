<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Galleries Admin Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Galleries_admin extends Backend {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		
		// Restricted access
		Auth::restrict('editor');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function index()
	{
		$this->view = false;
		echo 'Galleries';
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Galleries_admin


/* End of file galleries_admin.php */