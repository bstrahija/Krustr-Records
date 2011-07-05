<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
/**
 * Entry Revision Model
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2009, Boris Strahija, Creo
 * @version 	0.1
 */

class Entry_revision_m extends MY_Model {
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function get_list($entry_id = null, $limit = 20)
	{
		if ($entry_id) {
			$revisions = $this->db->select('er.id, er.entry_id, er.title, er.created_at, u.email, u.username, um.display_name')
						->from($this->_table.' AS er')
						->join('users AS u', 		'u.id = er.user_id')
						->join('user_meta AS um', 	'um.user_id = er.user_id')
						->where('er.entry_id', $entry_id)
						->limit($limit)
						->order_by('er.created_at', 'DESC')->get()->result();
			
			
			return $revisions;
			
		} // end if
		
		return null;
		
	} // end get_list()
			
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Entry_revision_m


/* End of file entry_revision_m.php */