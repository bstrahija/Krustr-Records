<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Comments Admin Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Comments_admin extends Backend {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Restricted access
		Auth::restrict('editor');
		
		// Set navigation mark
		$this->set_nav_mark('content');
		$this->set_nav_mark('comments', 2);
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		// Get latest comments
		$comments = CMS::$data->comments = $this->comment_m->get_all();
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Comments_admin


/* End of file comments_admin.php */