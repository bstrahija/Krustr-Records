<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Backend Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2009, Boris Strahija, Creo
 * @version 	0.2
 */

class Backend extends CMS {
	
	protected $form_fields = array(); 			// Form fields for form library
	protected $view; 							// The view to load, only set if you want to bypass the autoload magic.
	protected $rendered = ''; 					// Rendered data is stored here
	protected $layout 	= 'layouts/default'; 	// Default layout
	static $name 		= 'Undefined';
	public $section 	= '';
	static $mark1 		= null;
	static $mark2 		= null;
	static $mark3 		= null;
	static $mark4 		= null;
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function __construct()
	{
		// Only for the backend
		if ($this->uri->segment(1) != BACKEND) redirect(BACKEND);
		
		// Call the MY_Controller constructor
		parent::__construct();
		
		// Load resources
		$this->benchmark->mark('KR_Backend_Load_Libraries_start');
		$this->load->library('authentication/auth');
		
		// Try to load theme specific settings
		$assets_cfg = @$this->config->load('assets/assets', false, true);
		$this->load->library('assets/assets', $assets_cfg);
		

		$this->load->library('assets/assets', null);
		$this->benchmark->mark('KR_Backend_Load_Libraries_end');

		// Helpers
		$this->benchmark->mark('KR_Backend_Load_Helpers_start');
		$this->load->helper('backend_navigation');
		$this->load->helper('content/cms');
		$this->benchmark->mark('KR_Backend_Load_Helpers_end');
		
		// Configs
		$this->benchmark->mark('KR_Backend_Load_Config_start');
		//$this->lang->load('buttons');
		//$this->lang->load('nav');
		//$this->lang->load('validation');
		//$this->lang->load('krustr');
		$this->config->load('navigation');
		$this->benchmark->mark('KR_Backend_Load_Config_end');

		// Multilang
		if ($this->config->item('multilang')) {
			if ($this->session->userdata('kr_edit_lang')) define('LANG', $this->session->userdata('kr_edit_lang'));
			else                                          define('LANG', $this->config->item('default_lang'));
		}
		else {
			define('LANG', $this->config->item('default_lang'));
		} // end if

		
		// Restricted access
		Auth::restrict('editor');
		
		
		// No layout if Ajax request
		if ($this->input->is_ajax_request()) $this->layout = false;
		
		//$this->assets->configure($assets_config);
		
		// Add CSS assets
		//$this->assets->css(array('init.css', 'style.css'));
		
		// Add JS assets
		//$this->assets->js(array('libs/modernizr-1.6.js', 'libs/jquery-1.4.4.js', 'libs/jquery.fancybox-1.3.4.js', 'plugins.js', 'script.js', ));
		
		
		
		
		
		// Load resources
		/*$this->benchmark->mark('KR_Backend_Load_Libraries_start');
		$this->load->library('authentication/auth');
		$this->load->library('assets/assets');
		$this->load->library('layout');
		$this->load->library('notice');
		$this->load->library('user_agent');
		$this->load->library('pagination');
		$this->load->library('option');
		$this->load->library('video_embed');
		$this->benchmark->mark('KR_Backend_Load_Libraries_end');

		$this->benchmark->mark('KR_Backend_Load_Helpers_start');
		$this->load->helper('backend_navigation');
		$this->load->helper('ajax');
		$this->load->helper('content/cms');
		$this->benchmark->mark('KR_Backend_Load_Helpers_end');
		
		$this->benchmark->mark('KR_Backend_Load_Config_start');
		$this->lang->load('buttons');
		$this->lang->load('nav');
		$this->lang->load('validation');
		$this->lang->load('krustr');
		$this->config->load('navigation');
		$this->benchmark->mark('KR_Backend_Load_Config_end');
		

		$this->benchmark->mark('KR_Backend_Load_Assets_start');
		
		// Check browser
		$this->_check_browser();
		
		// Set the controller name
		$this->name = strtolower(get_class($this));
		
		// Set the section
		if ($this->name != $this->uri->segment(1)) :
			$this->section = $this->uri->segment(1);
			$this->data['title'] = $this->name;
		endif;
		
		// Add CSS assets
		$this->assets->css('init.css');
		$this->assets->css('libs/iconic.css');
		$this->assets->css('libs/pictos.css');
		$this->assets->css('libs/ui.themes/ui-darkness/jquery-ui-1.8.1.custom.css');
		$this->assets->css('libs/colorbox.css');
		$this->assets->css('libs/uniform/uniform.default.css');
		$this->assets->css('style.css');
		$this->assets->css('login.css');
		$this->assets->css('aside.css');
		$this->assets->css('navigation.css');
		$this->assets->css('forms.css');
		$this->assets->css('filters.css');
		$this->assets->css('print.css');
		
		// No layout if Ajax request
		if (is_ajax()) $this->layout = false;
		
		// Load backend theme
		if ( ! $this->option->get('backend_theme')) :
			$this->option->update('backend_theme', 'default');
		endif;
		$backend_theme_style = site_url('system/assets/themes/'.$this->option->get('backend_theme').'/css/theme.css');
		$this->assets->css($backend_theme_style);
		
		// Add JS assets
		$this->assets->js('libs/modernizr-1.6.min.js');
		$this->assets->js('libs/jquery-1.4.4.js');
		$this->assets->js('libs/jquery.ui/jquery-ui.js');
			// Rich editor
			if ($this->config->item('rich_editor', 'krustr') == 'textile') :
								
			elseif ($this->config->item('rich_editor', 'krustr') == 'wysiwyg') :
				$this->assets->js('libs/tiny_mce/jquery.tinymce.js');
				$this->assets->js('libs/tiny_mce_init.js');
				
			endif;
		$this->assets->js('plugins.js');
		$this->assets->js('libs/jquery.colorbox-min.js');
		$this->assets->js('libs/jquery.uniform.js');
		$this->assets->js('libs/jquery.form.js');
		$this->assets->js('script.js');
		//$this->assets->empty_cache();
		
		// More resources
		$this->load->library('grid/grid_lib');
		$this->benchmark->mark('KR_Backend_Load_Assets_end');*/
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function set_nav_mark($mark = null, $num = 1)
	{
		CMS::$data->nav_mark[$num] = $mark;
		
	} //end nav_mark()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_nav_mark($num = 1)
	{
		return @CMS::$data->nav_mark[$num];
		
	} //end get_nav_mark()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function set_title($title = null)
	{
		if ($title) CMS::$data->title = $title;
		return $title;
		
	} // end set_title()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_title()
	{
		if (isset(CMS::$data->title)) return CMS::$data->title;
		
	} // end get_title()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function set_data($var = null, $val = null)
	{
		if ($var and $val) CMS::$data->$var = $val;
		return $val;
		
	} // end set_data()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_data($var = null)
	{
		if ($var and isset(CMS::$data->$var)) return CMS::$data->$var;
		
	} // end get_data()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function get_all_data()
	{
		return CMS::$data;
		
	} // end get_all_data()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function add_action_button($label = null, $action = null, $class = null)
	{
		if ($label and $action) {
			CMS::$data->action_buttons[$label] = array(
				 'link' 	=> $action
				,'class' 	=> $class
			);
		} // end if
		
	} // end add_action_button()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * This function is called automatic by CI
	 * 
	 */
	public function _remap($method)
	{
		if (method_exists($this, $method)) :
			call_user_func_array(array($this, $method), array_slice($this->uri->rsegments, 2));
		
		else :
			if (method_exists($this, '_404')) :
				call_user_func_array(array($this, '_404'), array($method));	
			else :
				show_404(strtolower(get_class($this)).'/'.$method);
			endif;
		endif;
		
		$this->_load_view();
		
	} //end _remap()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _load_view()
	{
		if ($this->view !== false) :
			// Figure out the view
			$view = ($this->view !== null) ? $this->view . '.php' : $this->router->method . '.php';
			
			// Add rendered view to $yield var
			$data = new stdClass();
			$data->yield = $this->load->view($view, CMS::$data, true);
			
			// And merge with existing data
			$data = object_merge(CMS::$data, $data);
			
			// Load the view into the layout or display it if no layout exists
			if ( ! isset($this->layout)) :
				if (file_exists(APPPATH . 'views/layouts/' . $this->router->class . '.php')) :
					$this->load->view('layouts/' . $this->router->class . '.php', $data);
				else :
					$this->load->view('layouts/application.php', $data);
				endif;
			
			// Layout is defined
			elseif ($this->layout !== false) :
				$this->load->view($this->layout, $data);
			
			// No layout
			else :
				$this->output->set_output($data->yield);
			
			endif;
			
		endif;
		
	} //end _load_view()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function _check_browser()
	{
		if (
			   ($this->agent->browser() == 'Safari' 			AND $this->agent->version() >= '5.0')
			OR ($this->agent->browser() == 'Chrome' 			AND $this->agent->version() >= '5.0')
			OR ($this->agent->browser() == 'Firefox' 			AND $this->agent->version() >= '3.5')
			OR ($this->agent->browser() == 'Mozilla' 			AND $this->agent->version() >= '5.0')
			//OR ($this->agent->browser() == 'Internet Explorer' 	AND $this->agent->version() >= '7.0')
			) {
			return;
		}
		else {
			if ($this->router->class != 'browser') admin_redirect('browser/unsupported');
			
		} // end if
		
	} // end _check_browser()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Backend


/* End of file backend.php */