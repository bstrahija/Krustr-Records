<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* The MX_Controller class is autoloaded as required */



/**
 * MY_Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class MY_Controller extends MX_Controller {
	
	public $controller;
	public $method;
	public $module;
	public $module_details;

	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load some resources needed everywhere
		$this->load->model(array('content/entry_m', 'modules/module_m'));
		$this->load->helper(array('debug', 'browser', 'array'));
		$this->load->library(array('tinyo'));
		
        // Work out module, controller and method and make them accessable throught the CI instance
        $this->module 				= $this->router->fetch_module();
        $this->controller			= $this->router->fetch_class();
        $this->method 				= $this->router->fetch_method();

		// Get module info
		$this->module_details 		= $this->module_m->get_by('slug', $this->module);

		// If the module is disabled, then show a 404.
		empty($this->module_details->active) AND show_404();

		// Setup caching (driver is set in krustr.php config file)
		$this->load->driver('cache', array('adapter' => CACHE_DRIVER, 'backup' => 'file'));
		
		// Configuration
		$this->config->load('krustr');
		
		// Setup ID obfuscation
		Tinyo::$set = $this->config->item('tinyo_set');
		
		// Profiling
		$this->output->enable_profiler(TRUE);
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end MY_Controller



class Core extends MX_Controller {
	
	/**
	 *
	 */
	function __construct()
	{
		parent::__construct();
	}	
}



class CMS extends MY_Controller {
	
	public static $front_data; // Data passed to frontend
	public static $data; // Data passed to backend
	public static $uri_segment  = array();
	
	// Setup some paths
	public static $current_theme          = null;
	public static $themes_path            = 'addons/themes/';
	public static $themes_abs_path        = null;
	public static $current_theme_path     = null;
	public static $current_theme_abs_path = null;
	
	// All the channels (content types)
	public static $channels = array();
	
	// Enable or disable template tags
	public static $enable_parser = true;
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		if ( ! self::$front_data) {
			self::$front_data = new stdClass();
			self::$data       = new stdClass();
			
			// Defaults
			self::$data->yield  = '';
			
			// Load the recources
			$this->benchmark->mark('KR_Load_Models_start');
			$this->load->model(array(
				 'content/entry_m'
				,'content/published_m'
				,'content/entry_meta_tag_m'
				,'categories/category_m'
				,'comments/comment_m'
				,'channels/channel_m'
				,'fields/field_m'
				,'fields/field_content_m'
				,'setting_m'
				,'variables/variable_m'
			));
			$this->benchmark->mark('KR_Load_Models_end');
			
			// Load all the required libraries
			$this->benchmark->mark('KR_Load_Libraries_start');
			
			// Some required libraries and helpers
			$this->load->helper('content/cms');
			$this->load->helper('browser');
			$this->load->library('option');
			$this->load->library('form');
			$this->load->library('image_resize');
			$this->load->library('authentication/auth');
			$this->load->library('video_embed');
			$this->load->library('comments/comment');
			
			// Load HTML purifier
			include(APPPATH.'/third_party/html_purifier/HTMLPurifier.auto.php');
			
			// Load the settings from the database
			$this->_load_settings();
			
			// Get all channels and fields
			$this->_get_channels();
			
			// Set theme path
			self::$current_theme_path = self::$themes_path.CMS::$current_theme;
			
			// Get theme absolute path
			self::$themes_abs_path = realpath(self::$themes_path).'/';
			self::$current_theme_abs_path = realpath(self::$current_theme_path).'/';
			
			// Load the theme helper
			$this->load->helper('theme');
		}
		
	} // end __construct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_view_data()
	{
		return self::$front_data;
		
	} // end get_view_data()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _load_settings()
	{
		$this->settings = new stdClass();
		
		// Load from DB
		$settings = $this->option->get_all();
		
		// Add to settings object
		foreach ($settings as $setting) {
			$this->settings->{$setting->slug} = $setting->value;
		} // end foreach
		
		// And add settings to global view data
		CMS::$front_data->settings = $this->settings;
		
		// Some global settings
		CMS::$front_data->site_name        = $this->settings->site_name;
		CMS::$front_data->site_url         = site_url('hehehe');
		CMS::$front_data->site_slogan      = $this->settings->site_slogan;
		CMS::$front_data->meta_title       = $this->settings->meta_title;
		CMS::$front_data->meta_description = $this->settings->meta_description;
		CMS::$front_data->meta_keywords    = $this->settings->meta_keywords;
		
		// Get selected theme
		CMS::$current_theme = $this->settings->theme;
		
		// Get all translations
		//CMS::$front_data->lang = $this->lang;
		
		return $settings;
		
	} // end _load_settings()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _get_channels()
	{
		$channels 	= $this->channel_m->order_by('order_key')->get_many_by('type', 'channel');
		$fields 	= $this->field_m->get_many_by('status', 'active');
		
		foreach ($channels as $channel) {
			$tmp_channel = $channel;
			$tmp_channel->fields = array();
			
			// Find fields
			foreach ($fields as $field) {
				if ($field->channel_id == $channel->id) $tmp_channel->fields[$field->slug] = $field;
			} // end foreach
			
			self::$channels[$channel->slug] = $tmp_channel;
		} // end foreach
		
	} // end _get_channels()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} // end CMS


/* Load some required classes */
require APPPATH."libraries/backend.php";
require APPPATH."libraries/module.php";


/* End of file MY_Controller.php */