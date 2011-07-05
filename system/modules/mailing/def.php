<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
class Def_Mailing extends Module {
	
	/* ------------------------------------------------------------------------------------------ */
	
	function info()
	{
		return array(
			 'name'    => 'Mailing'
			,'version' => '1.0'
			,'description' => 'All thing email.'
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
		return '<h4>Mailing help</h4>';
		
	} // end help()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Def_Mailing
