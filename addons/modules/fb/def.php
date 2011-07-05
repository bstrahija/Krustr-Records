<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
class Def_Fb extends Module {
	
	/* ------------------------------------------------------------------------------------------ */
	
	function info()
	{
		return array(
			 'name'    => 'Facebook'
			,'version' => '1.0'
			,'description' => 'Facebook connect.'
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
		return '<h4>Facebook help</h4>';
		
	} // end help()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Def_Fb
