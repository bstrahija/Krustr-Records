<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Custom Field Model
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.1
 */

class Field_m extends MY_Model {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function active()
	{
		$this->db->where('status !=', 'trashed');
		
		return $this;
		
	} // end active()
	
	/* ------------------------------------------------------------------------------------------ */
	
	
} //end Field_m


/* End of file field_m.php */