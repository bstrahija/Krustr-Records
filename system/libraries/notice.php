<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Notice Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Notice {
	
	private static $ci;
	private static $_notices = array();
	private static $_wrap = array('<div class="notice %type">', '</div>');
	private static $_wrap_item = array('<p>', '</p>');
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		self::$ci =& get_instance();
		self::$ci->load->library('session');
		
		// Get all notices from session
		self::_get_notices();
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function add($notice = null, $type = 'message')
	{
		self::$_notices[$type][] = $notice;
		self::$ci->session->set_userdata('notices', self::$_notices);
		
	} //end add()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function display($type = null)
	{
		// Display specific notice type
		if ($type) {
			echo self::_display_type($type);
		}
		
		// Or display all types
		else {
			if (self::$_notices) {
				$all = '';
				foreach (self::$_notices as $_type=>$_notices) {
					$all .= self::_display_type($_type);
				} // end foreach
				echo $all;
			} // end if
		} // end if
		
		// Remove notices once they're displayed
		self::_remove_notices();
		
	} // end display()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function get($type = null)
	{
		if ($type) {
			return @self::$_notices[$type][0];
		} // end if
		
		return null;
		
	} // end get()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function get_all()
	{
		return @self::$_notices;
		
	} // end get_all()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private static function _display_type($type = 'message')
	{
		$notices = @self::$_notices[$type];
		if ($notices) {
			$data = str_replace('%type', $type, self::$_wrap[0]);
			foreach ($notices as $notice) {
				$data .= self::$_wrap_item[0].$notice.self::$_wrap_item[1];
			} // end foreach
			$data .= self::$_wrap[1];
			
			return $data;
		} // end if
		
		return null;
		
	} // end _display_type()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Gets all notices from session and puts them into the $_notices array
	 * It also removes the data from the session
	 *
	 */
	private static function _get_notices()
	{
		$notices = self::$ci->session->userdata('notices');
		self::$ci->session->unset_userdata('notices');
		if ($notices) self::$_notices = $notices;
		
	} // end _get_notices()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private static function _remove_notices()
	{
		self::$ci->session->unset_userdata('notices');
		self::$_notices = array();
		
	} // end _remove_notices()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Notice


/* End of file notice.php */