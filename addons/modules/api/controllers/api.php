<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * API Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

// REST library
require ADDONPATH.'/modules/api/libraries/rest_controller.php';

class Api extends REST_Controller {
	
	// Displayed data columns
	protected $user_columns = array('username', 'first_name', 'last_name', 'email', 'city', 'country');
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function __construct()
	{
		parent::__construct();
		
		// Load resources
		$this->load->model(array('users/user_m', 'content/entry_m'));
		$this->load->library(array('authentication/auth'));
		$this->load->helper(array('array', 'krustr'));
		
	} // end __construct()
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function user_get()
	{
		if ( ! $this->get('id')) $this->response(NULL, 400);
		
		// Get the user
		$user = object_to_array(Auth::get_user($this->get('id')));
		
		if ($user) {
			// Show only some data
			$user = elements($this->user_columns, $user);
			$this->response($user, 200);
		}
		else {
			$this->response(array('error' => "User could not be found"), 404);
		} // end if
		
	} //end user_get()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function users_get()
	{
		$users = $this->user_m->get_all_extended();
		
		if ($users) {
			foreach ($users as $key=>$user) {
				$users[$key] = elements($this->user_columns, object_to_array($user));
			} // end foreach
			
			$this->response($users, 200);
		}
		else {
			$this->response(array('error' => "Couldn't find any users"), 400);
		} // end if
		
	} // end users_get()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function entry_get()
	{
		if ( ! $this->get('id')) $this->response(NULL, 400);
		
		// Get the user
		$entry = object_to_array($this->entry_m->get_extended($this->get('id')));
		
		if ($entry) {
			$this->response($entry, 200);
		}
		else {
			$this->response(array('error' => "Entry could not be found"), 404);
		} // end if
		
	} // end entry_get()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function entries_get()
	{
		$entries = $this->entry_m->get_all();
		
		if ($entries) {
			foreach ($entries as $key=>$entry) {
				$entries[$key] = object_to_array($entry);
			} // end foreach
			$this->response($entries, 200);
		}
		else {
			$this->response(array('error' => "Couldn't find any entries"), 400);
		} // end if
		
	} // end entries_get()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end 


/* End of file .php */