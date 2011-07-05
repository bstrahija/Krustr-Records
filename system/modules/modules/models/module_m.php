<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Module Model
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Module_m extends MY_Model {
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function add($module = null)
	{
		if ($module) {
			$this->insert(array(
				 'title'       => $module['name']
				,'slug'        => $module['slug']
				,'version'     => $module['version']
				,'description' => $module['description']
				,'active'      => !empty($module['active'])
				,'installed'   => !empty($module['installed'])
				,'is_core'     => !empty($module['is_core'])
			));
			
		} // end if
		
	} // end add()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Module_m


/* End of file module_m.php */