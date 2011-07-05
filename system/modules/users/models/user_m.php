<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * User Model
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class User_m extends MY_Model {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Returns extended user data (group, meta etc.)
	 *
	 */
	function get_extended($key = NULL, $get_by = 'id')
	{
		if ($key) {
			// Select data
			$this->db->select('u.*, m.*, u.id AS id')->from($this->_table.' AS u');
			
			// Set condition (user or user meta table)
			if ($get_by == 'id' or $get_by == 'username' or  $get_by == 'email' or  $get_by == 'login_key'
				or  $get_by == 'remember_key' or  $get_by == 'activation_key' or  $get_by == 'forgot_password_key'
			) {
				$this->db->where('u.'.$get_by, $key);
			}
			elseif (in_array($get_by, $this->config->item('meta_columns', 'auth'))) {
				$this->db->where('m.'.$get_by, $key);
			} // end if
			
			// Join meta data
			$this->db->join('user_meta AS m', 'm.user_id = u.id', 'left');
			
			// Get it
			$user = $this->db->get()->row();
			
			return $user;
			
		} // end if
		
		return null;
		
	} //end get_extended()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function get_all_extended($order_by = 'u.created_at DESC')
	{
		// Select data
		$this->db->select('u.*, m.*, u.id AS id')->from($this->_table.' AS u');
		
		// Join meta data
		$this->db->join('user_meta AS m', 'm.user_id = u.id', 'left');
		
		// Don't show trashed users
		$this->db->where('u.status !=', 'trashed');
		
		// Order
		$this->db->order_by($order_by);
		
		// Get it
		$user = $this->db->get()->result();
		
		return $user;
		
	} //end get_all_extended()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function is_username_unique($username = null)
	{
		// Try to get user
		$user = $this->get_extended($username, 'username');
		
		if ($user) return false;
		
		return true;
		
	} // end is_username_unique()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function is_email_unique($email = null)
	{
		// Try to get user
		$user = $this->get_extended($email, 'email');
		
		if ($user) return false;
		
		return true;
		
	} // end is_email_unique()
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end User_m


/* End of file user_m.php */