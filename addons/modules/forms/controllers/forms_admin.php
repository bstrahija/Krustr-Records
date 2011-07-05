<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	

/**
 * Forms Admin Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Forms_admin extends Backend {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Restricted access
		Auth::restrict('superadmin');
		
		// Set navigation mark
		$this->set_nav_mark('forms');
		$this->set_nav_mark('forms', 2);
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		
		
	} // index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function entries()
	{
		// Set navigation mark
		$this->set_nav_mark('entries', 2);
		
		
	} // entries()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Forms_admin


/* End of file forms_admin.php */