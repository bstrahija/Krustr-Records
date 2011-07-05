<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY_Form_validation Class
 *
 * Extends Form_Validation library
 *
 * Adds one validation rule, "unique" and accepts a
 * parameter, the name of the table and column that
 * you are checking, specified in the forum table.column
 *
 * Note that this update should be used with the
 * form_validation library introduced in CI 1.7.0
 */
 
class MY_Form_validation extends CI_Form_validation {
	
	private $_ci;

	public function __construct()
	{
		$this->_ci =& get_instance();
		
	    parent::__construct();
	}
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function set_error($error = '')
	{
		if (empty($error)) {
			return false;
			
		}
		else {
			$this->_ci->form_validation->_error_array['custom_error'] = $error;
			return true;
			
		} // end if
		
	} // end set_error()

	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Unique
	 *
	 * @access	public
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	function unique($str, $field)
	{
		list($table, $column) = explode('.', $field, 2);

		$this->_ci->form_validation->set_message('unique', 'The %s that you requested is unavailable.');

		$query = $this->_ci->db->query("SELECT COUNT(*) AS dupe FROM ".$this->_ci->db->dbprefix($table)." WHERE $column = '$str'");
		$row = $query->row();
		
		return ((int) $row->dupe > 0) ? false : true;
		
	} // end unique()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function unique_except($str, $field)
	{
		$except = explode(";", $field);
		$field  = $except[0];
		$id     = $except[1];
		
		list($table, $column) = explode('.', $field, 2);

		$this->_ci->form_validation->set_message('unique_except', 'The %s that you requested is unavailable.');

		$query = $this->_ci->db->query("SELECT COUNT(*) AS dupe FROM ".$this->_ci->db->dbprefix($table)." WHERE id <> $id AND $column = '$str'");
		$row = $query->row();
		
		return ((int) $row->dupe > 0) ? false : true;
		
	} // end unique_except()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} // end MY_Form_validation

/* End of file MY_Form_validation.php */