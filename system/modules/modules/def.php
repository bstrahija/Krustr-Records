<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
class Def_Modules extends Module {
	
	/* ------------------------------------------------------------------------------------------ */
	
	function info()
	{
		return array(
			 'name'    => 'Modules'
			,'version' => '1.0'
			,'description' => 'Module management.'
		);
		
	} // end info()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function install()
	{
		
	} // end install()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function uninstall()
	{
		
	} // end uninstall()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function help()
	{
		return '<h4>Modules help</h4>';
		
	} // end help()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Def_Modules
