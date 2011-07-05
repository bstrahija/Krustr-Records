<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Comments Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Comment {
	
	private static $_ci;
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function __construct()
	{
		self::$_ci =& get_instance();
		
		// Load resources
		self::$_ci->config->load('comments/akismet');
		self::$_ci->load->model('comments/comment_m');
		self::$_ci->load->library('comments/akismet');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Set parent_id to false if no tree is required
	 */
	function get_all($entry_id = null, $parent_id = null)
	{
		if ($entry_id) {
			$data = array('entry_id'=>$entry_id, 'status'=>'published');
			if ($parent_id) 				$data['parent_id'] = $parent_id;
			elseif ($parent_id === null) 	$data['parent_id'] = NULL;
			
			return self::$_ci->comment_m->get_many_by($data);
		} // end if
		
		return null;
		
	} // end get_all()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function add($comment = null, $data = null)
	{
		if ($comment and $data) {
			// Prepare Akismet
			$config = array(
				 'blog_url' => self::$_ci->config->item('site_url', 'akismet')
				,'api_key' 	=> self::$_ci->config->item('key', 'akismet')
				,'comment' 	=> $comment
			);
			
			// Init Akismet
			self::$_ci->akismet->init($config);
			
			// Check for errors
			if (self::$_ci->akismet->errors_exist()) :
				$data['status'] = 'spam';
			else :
				$data['status'] = 'published';
			endif;
			
			// Insert to database
			$comment_id = self::$_ci->comment_m->insert($data);
			
			// Send notification
			$this->_send_comment_notification($comment_id);
			
			return $comment_id;
			
		} // end if
		
	} //end add()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _send_comment_notification($comment_id = null)
	{
		if ($comment_id) {
			$comment = self::$_ci->comment_m->get($comment_id);
			
		} // end if
		
	} // end _send_comment_notification()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Check if the comment is spam
	 *
	 */
	function _validate($data = null)
	{
		
	} // end _validate()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end 


/* End of file .php */