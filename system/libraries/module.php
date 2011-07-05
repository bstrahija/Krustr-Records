<?php  defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Krustr abstract module
 * 
 * from PyroCMS by Phil Sturgeon
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.30
 */

abstract class Module {

	public abstract function info();

	public abstract function install();

	public abstract function uninstall();

	public function __construct()
	{
		$this->load->database();
		$this->load->dbforge();
	}
	
	public function help()
	{
		return 'No help available.';
	}

	/**
	 * __get
	 *
	 * Allows this class and classes that extend this to use $this-> just like
	 * you were in a controller.
	 *
	 * @access	public
	 * @return	mixed
	 */
	public function __get($var)
	{
		static $ci;
		isset($ci) OR $ci =& get_instance();
		return $ci->{$var};
	}
}

/* End of file Module.php */