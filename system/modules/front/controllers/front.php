<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Front Main Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Front extends CMS {
	
	// Setup theme and object instances
	public $settings;
	public $theme_instance 		 = 'theme'; 	// Instance name for the theme object
	public $request_instance 	 = 'request'; 	// Instance that is used for routing the requests
	public $template_instance 	 = 'template'; 	// Instance that is used for parsing the theme templates
	public $actions_instance 	 = 'actions'; 	// Instance that is used for all theme form actions
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		
		// Try to load theme specific settings
		$assets_cfg = $this->config->load('../../../'.ADDONPATH.'themes/'.CMS::$current_theme.'/config/assets', false, false);
		$this->load->library('assets/assets', $assets_cfg);
		
		// Set view data
		$this->_set_view_data();
		
		// Some required libraries and helpers
		$this->load->library('parser');
		
		
		// Krustr classes (benchmarked)
		$this->benchmark->mark('KR_Load_Request_Library_start');
			$this->load->library('kr_request', 	null, $this->request_instance);
		$this->benchmark->mark('KR_Load_Request_Library_end');
		
		$this->benchmark->mark('KR_Load_Template_Library_start');
			$this->load->library('kr_template', 	null, $this->template_instance);
		$this->benchmark->mark('KR_Load_Template_Library_end');
		
		$this->benchmark->mark('KR_Load_Theme_Library_start');
			$this->load->library('kr_theme', 		null, $this->theme_instance);
		$this->benchmark->mark('KR_Load_Theme_Library_end');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		// First check for maintenance mode
		if (is_admin() or $this->option->get('site_status') == 'online') {
			$this->_start();
		}
		else {
			// Check if custom template exists
			if (is_file('addons/themes/'.self::$current_theme.'/offline.php')) {
				$this->load->view('../../addons/themes/'.self::$current_theme.'/offline', self::$view_data);
			}
			else {
				echo $this->option->get('site_offline_message');
			} // end if
		} // end if
		
	} // end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _start()
	{
		$this->benchmark->mark('KR_Start_start');
		$this->template->render();
		$this->benchmark->mark('KR_Start_end');
		
	} // end _start()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _set_view_data()
	{
		// Add some global variables
		CMS::$front_data->site_url 	= url();

		// Add some benchmarking stuff
		CMS::$front_data->benchmark->elapsed_time = $this->benchmark->elapsed_time().'sec';
		CMS::$front_data->benchmark->memory_usage = $this->benchmark->memory_usage();
		CMS::$front_data->benchmark->memory_peak  = number_format(memory_get_peak_usage()/1024/1024, 2).'MB';
		
		// Theme data
		CMS::$front_data->theme_url          = theme_url();
		CMS::$front_data->theme->name        = CMS::$front_data->theme_name = $this->option->get('theme');
		CMS::$front_data->theme->path        = CMS::$front_data->theme_path = self::$current_theme_path;
		CMS::$front_data->assets_url         = theme_url('assets');
		
		// URL's
		CMS::$front_data->url->current       = current_url();
		CMS::$front_data->url->site          = url();
		
		// User
		CMS::$front_data->logged_in          = logged_in();
		CMS::$front_data->user->logged_in    = CMS::$front_data->logged_in;
		CMS::$front_data->user->username     = username();
		CMS::$front_data->user->email        = user_email();
		CMS::$front_data->user->display_name = user_display_name();
		
		// Variables
		CMS::$front_data->variables          = $this->variable_m->all();
		
		// Settings
		CMS::$front_data->settings           = $this->settings;
		
	} // end _set_view_data()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Front


/* End of file krustr.php */