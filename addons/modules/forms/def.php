<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
class Def_Forms extends Module {
	
	/* ------------------------------------------------------------------------------------------ */
	
	function info()
	{
		return array(
			 'name'    => 'Forms'
			,'version' => '1.0'
			,'description' => 'Creating and handling forms for the fontend. Can be contact forms or something similar.'
		);
		
	} // end info()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function install()
	{
		// !✰ Forms
		
		// Add the fields
		$this->dbforge->add_field('id');
		$db_fields = array
		(
			'title'       => array('type' => 'VARCHAR','constraint' => '255'),
			'slug'        => array('type' => 'VARCHAR','constraint' => '255'),
			'description' => array('type' =>'TEXT'),
			'fields'      => array('type' =>'TEXT'),
			'email'       => array('type' => 'VARCHAR','constraint' => '255'),
			'created_at'  => array('type' =>'INT','constraint' => '10'),
			'updated_at'  => array('type' =>'INT','constraint' => '10'),
		);
		$this->dbforge->add_field($db_fields);
		
		// Create the table
		$this->dbforge->create_table('forms', true);
		

		// !✰ Form entries
		
		// Add the fields
		$this->dbforge->add_field('id');
		$db_fields = array
		(
			'form_id'             => array('type' => 'INT','constraint' => '9'),
			'name'                => array('type' => 'VARCHAR','constraint' => '255'),
			'message'             => array('type' =>'TEXT'),
			'email'               => array('type' => 'VARCHAR','constraint' => '255'),
			'created_at'          => array('type' =>'INT','constraint' => '10'),
			'updated_at'          => array('type' =>'INT','constraint' => '10'),
		);
		$this->dbforge->add_field($db_fields);
		
		// Create the table
		$this->dbforge->create_table('form_entries', true);
		
	} // end install()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function uninstall()
	{
		$this->dbforge->drop_table('forms');
		$this->dbforge->drop_table('form_entries');
		
	} // end uninstall()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function help()
	{
		return '<h4>Forms help</h4>';
		
	} // end help()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Def_Forms
