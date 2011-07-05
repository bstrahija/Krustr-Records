<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Variable Model
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Variable_m extends MY_Model {

	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function all()
	{
		if ( ! $vars = $this->cache->get('variables')) {
			$tmp_vars = parent::order_by('title')->get_all();
			$vars = array();
			
			foreach ($tmp_vars as $var) {
				$vars[$var->title] = $var->value;
			} // end foreach

			$this->cache->save('variables', $vars, 60*60*24); // Cache for a day
		} // end if
		
		return $vars;
		
	} // end all()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Variable_m


/* End of file variable_m.php */