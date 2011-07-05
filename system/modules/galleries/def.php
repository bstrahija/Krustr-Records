<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
class Def_Galleries extends Module {
	
	/* ------------------------------------------------------------------------------------------ */
	
	function info()
	{
		return array(
			 'name'    => 'Galleries'
			,'version' => '1.0'
			,'description' => 'Image management.'
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
		return '<h4>Galleries help</h4>';
		
	} // end help()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Def_Galleries
