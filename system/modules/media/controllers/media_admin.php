<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Media Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Media_admin extends Backend {
	
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
		$this->set_nav_mark('media', 2);
		
		// Get channels
		$this->channels = Backend::$data->channels = $this->channel_m->order_by('title')->get_all();
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		Backend::set_title('Media manager');
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Media_admin


/* End of file media_admin.php */