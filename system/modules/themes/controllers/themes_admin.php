<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	

/**
 * Themes Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2009, Boris Strahija, Creo
 * @version 	0.2
 */

class Themes_admin extends Backend_Controller {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		
		// Set user restriction
		Auth::restrict('admin');
		
		// Set navigation mark
		$this->set_nav_mark('layout');
		$this->set_nav_mark('themes', 2);
		
		// Load some resources
		$this->load->library('option');
		$this->load->library('image_resize');
		$this->load->helper('directory');
		$this->load->helper('xml');
		
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function index()
	{
		$themes = array();
		$themes_folder = './addons/themes';
		$map = directory_map($themes_folder);
		
		// Get current theme
		Krustr::$data->current_theme = $current_theme = $this->option->get('theme');
		
		// Now go into every folder and file the theme info file
		foreach ($map as $folder=>$contents) {
			if (is_array($contents)) {
				$info = @simplexml_load_file($themes_folder.'/'.$folder.'/theme.xml');
				if ($info) {
					$tmp_theme = $info;
					$tmp_theme->folder = $folder;
					$tmp_theme->path = $themes_folder.'/'.$folder;
					$tmp_theme->screenshot = $themes_folder.'/'.$folder.'/assets/theme/screenshot.jpg';
					$themes[] = $tmp_theme;
				} // end if
			} // end if
		} // end foreach
		
		// Add to view
		Krustr::$data->themes = $themes;
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function activate($name = NULL)
	{
		$this->view = FALSE;
		
		if ($name) {
			$this->option->update('theme', $name);
			Notice::set('Theme was activated.');
		
		}
		else {
			Notice::set('Error activating theme.', 'error');
		
		} // end if
		
		redirect_backend('themes');
		
	} // end activate()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function edit_file()
	{
		$file = './addons/themes/creo/index.php';
		Krustr::$data->content = read_file($file);
		
	} // end edit_file()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Themes_admin


/* End of file themes_admin.php */