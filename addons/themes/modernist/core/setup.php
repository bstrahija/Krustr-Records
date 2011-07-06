<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Setup entries for templates / pages
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Theme_setup extends CMS {
	
	private $_setup = array();
	private $_page_cache_ttl = 3600; // 1 hour
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Load some resources
		$this->load->library('carts/cart');
		$this->load->library('deals/deal');
		$this->load->library('maps/map');
		$this->load->config('ga/ga');
		
		// The array is setup in this way:
		// 'current_page_slug_or_id'=>array('slug_or_id_of_entry_to_load', 'array_index_to_load_the_entry_into')
		
		// For testing purposes only
		//$this->cache->clean();
		
		// Setup all the special contents
		$this->_setup = array
		(
			'index'=>array
			(
				array('slug'=>'welcome'),
			),
		);
		
		// Load 'em
		$this->benchmark->mark('Theme_Setup_Load_start');
		$this->_load();
		$this->benchmark->mark('Theme_Setup_Load_end');
		
		// You can set a custom site title if you want
		$this->_set_title();
		
		// And add your custom data if you like
		$this->benchmark->mark('Theme_Setup_Custom_start');
		$this->_setup_custom();
		$this->benchmark->mark('Theme_Setup_Custom_end');
		
		// Not a preview
		CMS::$front_data->is_preview = false;
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _load()
	{
		foreach ($this->_setup as $id=>$entries)
		{
			if (is_entry($id) or ($id == 'index' and is_home()))
			{
				foreach ($entries as $data)
				{
					// From channel
					if (isset($data['channel']))
					{
						CMS::$front_data->content->{$data['key']} = entries($data['channel'], @$data['limit']);
					}
					
					// Single entry
					else
					{
						// Load entry for front view
						if ( ! isset($data['key'])) // check if a key is set (this way you can acces the entry)
						{
							CMS::$front_data->content->{$data['slug']} = $this->publisher->get($data['slug']);
						}
						else
						{
							CMS::$front_data->content->{$data['key']} = $this->publisher->get($data['slug']);
						}
					}
				}
				
				return;
			}
		}
		
	} // _load()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _set_title()
	{
		CMS::$front_data->site_title = CMS::$front_data->site_name;
		
	} // end _set_title()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _setup_custom()
	{
		// !✰ Channel entries
		if (is_channel())
		{
			$entries = $this->publisher->get_in_channel('artists');
			
			if ($entries)
			{
				foreach ($entries as $key=>$entry)
				{
					$entries[$key]->permalink = url($entry->permalink);
				}
			}
			
			
			CMS::$front_data->entries = CMS::$front_data->content->entries = $entries;
		}
		
		
		// !✰ Get logged in user
		$this->benchmark->mark('Theme_Setup_Custom_Get_User_start');
		CMS::$front_data->profile = get_user();
		$this->benchmark->mark('Theme_Setup_Custom_Get_User_end');
		
		
		// !✰ Facebook
		$this->benchmark->mark('Theme_Setup_Custom_Facebook_Data_start');
		if (fb_logged_in())
		{
			CMS::$front_data->fb->logged_in      = 1;
			CMS::$front_data->logged_in          = 1;
			CMS::$front_data->user->logged_in    = 1;
			CMS::$front_data->user->display_name = @CMS::$front_data->profile->display_name;
			CMS::$front_data->user->email        = @CMS::$front_data->profile->email;
		}
		else
		{
			CMS::$front_data->fb->logged_in = 0;
		}
		$this->benchmark->mark('Theme_Setup_Custom_Facebook_Data_end');
		
	} // _setup_custom()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Check if the current page is a city page
	 */
	public function is_city()
	{
		if (isset(CMS::$front_data->category) and isset(CMS::$front_data->parent_category) and CMS::$front_data->parent_category->slug == 'gradovi')
		{
			return CMS::$front_data->category->id;
		}
		
		return false;
		
	} // is_city()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function clean_cache($entry_id = null)
	{
		$ci = get_instance();
		$ci->cache->clean();
		
	} // end clean_cache()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Theme_setup


/* End of file _setup.php */