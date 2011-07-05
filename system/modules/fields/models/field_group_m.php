<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Custom Field Group Model
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.1
 */

class Field_group_m extends MY_Model {
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_by_channel($channel_id = null, $skip_default = false)
	{
		// First we get the default groups
		if ($skip_default) {
			$groups = array();
		}
		else {	
			$groups = $this->db->where_in('type', array('default_group', 'custom_group', 'meta_tags'))->get($this->_table)->result();
		} // end if
		
		if ($channel_id) {
			$field_groups = $this->db->where('channel_id', $channel_id)->get($this->_table)->result();
			
			if ($field_groups) {
				$groups = array_merge($groups, $field_groups);
				osort($groups, 'order_key');
			} // end if
		} // end if
		
		return $groups;
		
	} // end get_by_channel()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function add_fields($groups = null, $fields = null)
	{
		if ($groups and $fields) {
			foreach ($groups as $key=>$group) {
				foreach ($fields as $field) {
					if ($group->id == $field->group_id) {
						if ( ! isset($group->fields)) $groups[$key]->fields = array();
						$groups[$key]->fields[] = $field;
					} // end if
				} // end foreach
			} // end foreach
		} // end if
		
		return $groups;
		
	} // end add_fields()
	
	/* ------------------------------------------------------------------------------------------ */
	
	
} //end Field_group_m


/* End of file field_group_m.php */