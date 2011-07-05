<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
/**
 * Settings Model
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Setting_m extends MY_Model {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function all()
	{
		$tmp_settings = parent::order_by('slug')->get_all();
		$settings = array();
		
		foreach ($tmp_settings as $s) {
			$settings[$s->slug] = $s->value;
		} // end foreach
		
		return $settings;
		
	} // end all()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Setting_m


/* End of file setting_m.php */