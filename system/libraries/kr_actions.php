<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Krustr Actions Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2009, Boris Strahija, Creo
 * @version 	0.30
 */

class KR_Actions extends CMS {
	
	/* ------------------------------------------------------------------------------------------ */

	/**
	 *
	 */
	public function __construct()
	{
		// Load resources
		$this->benchmark->mark('KR_Load_Actions_Library_Resources_start');
		$this->load->library('form_validation');
		$this->load->library('assets/assets');
		$this->load->library('notice');
		$this->load->model('galleries/gallery_m');
		$this->load->model('galleries/gallery_image_m');
		$this->benchmark->mark('KR_Load_Actions_Library_Resources_end');

		// Include actions for current theme
		$this->benchmark->mark('KR_Load_Actions_Library_Include_Theme_Actions_start');
		$actions_class = reduce_double_slashes(CMS::$current_theme_abs_path.'/core/actions.php');
		if (file_exists($actions_class)) include_once($actions_class);
		$this->benchmark->mark('KR_Load_Actions_Library_Include_Theme_Actions_end');
		
		// Initialize
		$this->benchmark->mark('KR_Load_Actions_Library_Initialize_Theme_Actions_start');
		if (class_exists('theme_actions')) $theme_actions = new theme_actions;
		$this->benchmark->mark('KR_Load_Actions_Library_Initialize_Theme_Actions_end');
		
		
		// Initialize the setup class (can be used to load entries on specific pages etc.)
		$this->benchmark->mark('KR_Load_Actions_Library_Initialize_Theme_Setup_start');
		$setup_class = reduce_double_slashes(CMS::$current_theme_abs_path.'/core/setup.php');
		if (file_exists($setup_class))   include_once($setup_class);
		if (class_exists('theme_setup')) $theme_setup = new Theme_setup;
		$this->benchmark->mark('KR_Load_Actions_Library_Initialize_Theme_Setup_end');
		
		// Language data
		$lang = array();
		if (isset($this->lang->language) and ! empty($this->lang->language))
		{
			foreach ($this->lang->language as $key=>$str)
			{
				$lang[ substr($key, 2) ] = $str;
			}
		}
		
		// Add to theme
		CMS::$front_data->lang = array_to_object($lang);
		
	} // end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	
} // end KR_Actions


/* End of file kr_actions.php */