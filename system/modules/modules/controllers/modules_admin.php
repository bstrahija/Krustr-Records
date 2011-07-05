<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Modules Admin Controller
 *
 * Inspired by PyroCMS
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Modules_admin extends Backend {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		
		// Restricted access
		Auth::restrict('superadmin');
		
		// Load resources
		$this->load->model('module_m');
		
		// Set navigation mark
		$this->set_nav_mark('system');
		$this->set_nav_mark('modules', 2);
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		// Scan modules
		$this->_scan_modules();
		
		// Now get all modules from DB
		CMS::$data->modules = $this->module_m->order_by('title')->get_all();
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function install($slug = null)
	{
		$this->view = false;
		
		// Run installation method
		if ($def_class = $this->_def_class($slug))
		{
			$def_class->install();
			$this->module_m->update_by(array('slug'=>$slug), array('installed'=>1));
			Notice::add('Module installed.');
		}
		else
		{
			Notice::add("Definition class doesn't exist for module.");
		}
		
		admin_redirect('modules');
		
	} // end install()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function uninstall($slug = null)
	{
		$this->view = false;
		
		// Run deinstallation method
		if ($def_class = $this->_def_class($slug))
		{
			$def_class->uninstall();
			$this->module_m->update_by(array('slug'=>$slug), array('installed'=>0));
			Notice::add('Module uninstalled.');
		}
		else
		{
			Notice::add("Definition class doesn't exist for module.");
		}
		
		admin_redirect('modules');
		
	} // end uninstall()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function activate($slug = null)
	{
		$this->view = false;
		$this->module_m->update_by(array('slug'=>$slug), array('active'=>1));
		Notice::add('Module activated.');
		admin_redirect('modules');
		
	} // end activate()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function deactivate($slug = null)
	{
		$this->view = false;
		$this->module_m->update_by(array('slug'=>$slug), array('active'=>0));
		Notice::add('Module deactivated.');
		admin_redirect('modules');
		
	} // end deactivate()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Scan modules folders for new modules
	 * 
	 */
	private function _scan_modules()
	{
		$is_core = true;
		
		// Get all existing modules
		$existing       = $this->module_m->get_all();
		$existing_array = array();
		if (is_array($existing) && count($existing) > 0) {
			foreach ($existing AS $module) {
				$existing_array = array_merge(array($module->slug), $existing_array);
			} // end foreach
		} // end if
		
		// Scan folders
		foreach (array(APPPATH, ADDONPATH) as $folder) {
			foreach (glob($folder.'modules/*', GLOB_ONLYDIR) as $module_name) {
				$slug = basename($module_name);
				
				// Check for a module definition
				if ( ! $def_class = $this->_def_class($slug, $is_core)) {
					continue;
				} // end if

				// Check if already in DB
				if (in_array($slug, $existing_array)) {
					continue;
				} // end if
				
				// Get some basic info
				$module = $def_class->info();
				
				// Now lets set some details ourselves
				$module['slug']      = $slug;
				$module['active']    = $is_core; // enable if core
				$module['installed'] = $is_core; // install if core
				$module['is_core']   = $is_core; // is core if core

				// Looks like it installed ok, add a record
				$this->module_m->add($module);
				
			} // end foreach
			
			$is_core = false;
		} // end foreach
		
	} // end _scan_modules()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Checks for module definition and returns it
	 *
	 */
	private function _def_class($slug = null, $is_core = false)
	{
		if ($slug) {
			$path = $is_core ? APPPATH : ADDONPATH;
			
			// Get module definition
			$def_file = $path . 'modules/' . $slug . '/def'.EXT;
			
			// Check the details file exists
			if ( ! is_file($def_file)) {
				return false;
			} // end if
			
			// include definition
			include_once $def_file;
	
			// Name of the definition class
			$class = 'Def_'.ucfirst(strtolower($slug));
	
			// Now we need to talk to it
			return class_exists($class) ? new $class : false;
			
		} // end if
		
		return false;
		
	} // end _def_class()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Modules_admin


/* End of file modules_admin.php */