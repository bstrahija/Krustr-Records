<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * CMS requests library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class KR_Request extends Front {
	
	// The trigger setup
	public $entry_trigger    = 'entry';    // If 1st segment is this string we are looking for an entry
	public $category_trigger = 'category'; // If 1st segment is this string we are looking for a category
	public $tag_trigger      = 'tag';      // If 1st segment is this string we are looking for a tag
	public $year_trigger     = 'y';        // eg. blog/y/2010
	public $month_trigger    = 'm';        // eg. blog/y/2010/05
	public $day_trigger      = 'd';        // eg. blog/y/2010/05/01
	public $archive_trigger  = 'archive';  // eg. archive/2010/05 or blog/archive/2010
	public $lang_triggers    = array();    // Language triggers read from the Krustr config file
	
	// Trigger vars
	public $trigger      = null; // Current trigger
	public $trigger_lang = null;
	
	// The view that will be loaded
	public static $load_view = 'index';
	
	// Entry object for single entry templates
	public static $entry = null;
	
	// Categories
	public $category        = null;
	public $parent_category = null;
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Start benchmark
		$this->benchmark->mark('KR_Request_Constructor_start');
		
		// Some required libraries and helpers
		$this->load->library('option');
		$this->load->library('authentication/auth');
		$this->load->library('content/publisher');
		
		// Get language triggers
		$languages = $this->config->item('langs');
		foreach ($languages as $language_key=>$language) :
			$this->lang_triggers[] = $language_key;
		endforeach;

		// Prepare $uri_segment array, different if multilang is enabled
		if (config_item('multilang') === true && in_array($this->uri->segment(1), $this->lang_triggers))
		{
			$this->trigger_lang = $this->uri->segment(1);
			
			// Prepare segments accordingly
			CMS::$uri_segment = $this->uri->segment_array();
			$tmp_uri_segment = array_slice(CMS::$uri_segment, 1);
			CMS::$uri_segment = array();
			
			foreach ($tmp_uri_segment as $key=>$us)
			{
				CMS::$uri_segment[$key+1] = $us;
			} // end foreach
		}
		else
		{
			CMS::$uri_segment = $this->uri->segment_array();
		}  // end if
		
		// Pass segments of to view
		CMS::$front_data->uri_segment = CMS::$uri_segment;
		
		// End benchmark
		$this->benchmark->mark('KR_Request_Constructor_end');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Return the path to the view that the template library should parse and load
	 *
	 */
	public function view()
	{
		// Override the request with mete_redirect
		$redirect = null;
		
		// Not a channel template by default
		CMS::$front_data->is_channel = false;
		CMS::$front_data->channel    = null;
			
		// Home page if no segments
		if (count(CMS::$uri_segment) == 0) {
			$this->load_view = 'index';
		}
		
		// Preview something?
		elseif (count(CMS::$uri_segment) > 0 and CMS::$uri_segment[1] == 'preview' and $this->_preview())
		{
			// All done in the condition, no need for any action
		}
		
		// Get by channel
		elseif ($this->_is_channel_trigger()) {
			CMS::$front_data->channel    = $this->channel;
			
			// Check if should show a single article
			if ($this->_is_article()) {
				$entry_slug = CMS::$uri_segment[2];
				
				// Channel specific template
				if ($this->_template_exists('entry_'.$this->trigger->url_trigger)) {
					$this->load_view = 'entry_'.$this->trigger->url_trigger;
				}
				elseif ($this->_template_exists('entry_'.$this->trigger->slug_singular)) {
					$this->load_view = 'entry_'.$this->trigger->slug_singular;
				}
				elseif ($this->_template_exists($this->trigger->slug_singular)) {
					$this->load_view = $this->trigger->slug_singular;
				}
				elseif ($this->_template_exists('entry_'.$this->trigger->slug)) {
					$this->load_view = 'entry_'.$this->trigger->slug;
				}
				elseif ($this->_template_exists($this->trigger->slug_singular)) {
					$this->load_view = $this->trigger->slug_singular;
				}
				
				// Entry global template
				elseif ($this->_template_exists('entry')) {
					$this->load_view = 'entry';
				}
				
				else {
					$this->load_view = 'index';
				} // end if
				
			}
			
			// If is a category in channel (TODO: create a method called is_category)
			elseif (@CMS::$uri_segment[2]) {
				CMS::$front_data->is_channel = true;
				
				// Channel specific template
				if ($this->_template_exists('channel_'.$this->trigger->url_trigger)) {
					$this->load_view = 'channel_'.$this->trigger->url_trigger;
				}
				elseif ($this->_template_exists('channel_'.$this->trigger->slug)) {
					$this->load_view = 'channel_'.$this->trigger->slug;
				}
				elseif ($this->_template_exists($this->trigger->slug)) {
					$this->load_view = $this->trigger->slug;
				}
				
				// Channel global template
				elseif ($this->_template_exists('channel')) {
					$this->load_view = 'channel';
				}
				
				else {
					$this->load_view = 'index';
				} // end if
			}
			
			elseif ( ! @CMS::$uri_segment[2]) {
				CMS::$front_data->is_channel = true;
				
				// Channel specific template
				if ($this->_template_exists('channel_'.$this->trigger->url_trigger)) {
					$this->load_view = 'channel_'.$this->trigger->url_trigger;
				}
				elseif ($this->_template_exists('channel_'.$this->trigger->slug)) {
					$this->load_view = 'channel_'.$this->trigger->slug;
				}
				elseif ($this->_template_exists($this->trigger->slug)) {
					$this->load_view = $this->trigger->slug;
				}
				
				// Channel global template
				elseif ($this->_template_exists('channel')) {
					$this->load_view = 'channel';
				}
				
				else {
					$this->load_view = 'index';
				} // end if
			}
			else {
				header("HTTP/1.0 404 Not Found");
				if (CMS::$uri_segment[1] == BACKEND) $this->load_view = '../../../system/views/layouts/404';
				else                                  $this->load_view = '404';
				
			} // end if
			
		}
		
		// Page template
		elseif ($this->_is_page() and $this->entry) {
			// Channel specific template
			if ($this->_template_exists('page_'.$this->entry->channel)) {
				$this->load_view = 'page_'.$this->entry->channel;
			}
			
			// Page specific template
			elseif ($this->_template_exists('page_'.$this->entry->slug)) {
				$this->load_view = 'page_'.$this->entry->slug;
			}
			
			// Page global template
			elseif ($this->_template_exists('page')) {
				$this->load_view = 'page';
			}
			
			// Entry global template
			elseif ($this->_template_exists('entry')) {
				$this->load_view = 'entry';
			}
			
			else {
				$this->load_view = 'index';
			} // end if
			
			// Set redirect
			if ($this->entry->meta_redirect) $redirect = $this->entry->meta_redirect;
			
		}
		
		// Category template
		elseif ($this->_is_category() and $this->category)
		{
			// Channel specific template
			if ($this->_template_exists('category_'.$this->category->slug)) {
				$this->load_view = 'category_'.$this->category->slug;
			}
			
			// By parent caetegory perhaps
			elseif ($this->parent_category and $this->_template_exists('category_'.$this->parent_category->slug)) {
				$this->load_view = 'category_'.$this->parent_category->slug;
			}
			
			// Category global template
			elseif ($this->_template_exists('category')) {
				$this->load_view = 'category';
			}
			
		}
		
		// Else 404 error
		else {
			header("HTTP/1.0 404 Not Found");
			if (CMS::$uri_segment[1] == BACKEND) $this->load_view = '../../../system/views/layouts/404';
			else                                  $this->load_view = '404';
			
		} // end if
		
		if ($redirect) 	redirect($redirect);
		else 			return reduce_double_slashes('../../'.CMS::$current_theme_path.'/'.$this->load_view);
		
	} // end view()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _is_article()
	{
		// First mak sure its in a channel and a slug is there
		if ($this->_is_channel_trigger() and count(CMS::$uri_segment) > 1 and CMS::$uri_segment[2] != $this->category_trigger) {
			// Find entry in database
			$entry = $this->publisher->get(CMS::$uri_segment[2]);
			
			if (isset($entry) and $entry) {
				// Find channel object for entry
				
				foreach (CMS::$channels as $ch) {
					if ($ch->id == $entry->channel_id) $channel = $this->channel = $ch;
				} // end foreach
				
				// Return and set entry
				if ($entry and $channel) {
					CMS::$front_data->channel        = $channel;
					CMS::$front_data->content->entry = $this->entry = $entry;
					return true;
				} // end if
			
			} // end if
			
		} // end if
		
		return false;
		
	} // end _is_article()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _is_page()
	{
		// First mak sure its not a trigger request
		if ( ! $this->_is_channel_trigger() and count(CMS::$uri_segment)) {
			// Find entry in database
			$page = $this->publisher->get(CMS::$uri_segment[1]);
			
			if ($page) {
				// Find channel object for entry
				$channel = null;
				foreach (CMS::$channels as $ch) {
					if ($ch->id == $page->channel_id) $channel = $this->channel = $ch;
				} // end foreach
			} // end if
			
			// Return and set entry
			if ($page and $channel and $channel->data_type == 'page') {
				CMS::$front_data->channel        = $channel;
				
				CMS::$front_data->content->entry = 
				CMS::$front_data->page = 
				$this->entry = $page;
				return true;
			} // end if
			
		} // end if
		
		return false;
		
	} //end _is_page()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _is_channel_trigger()
	{
		if (count(CMS::$uri_segment) >= 1) {
			// Loop through all channels and find the one requested
			$trigger = false;
			foreach (CMS::$channels as $channel) {
				if ($channel->url_trigger == CMS::$uri_segment[1] or $channel->slug == CMS::$uri_segment[1]) {
					$trigger = $this->channel = $channel;
					break;
				} // end if
			} // end foreach
			
			if ($trigger) {
				$this->trigger = $trigger;
				return true;
			} // end if
		} // end if
		
		return false;
		
	} // end _is_channel_trigger()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _is_category()
	{
		if (count(CMS::$uri_segment) >= 1)
		{
			// Find the category
			$category = $this->category_m->get_by('slug', CMS::$uri_segment[1]);
			
			if ($category)
			{
				$this->category = CMS::$front_data->category = $category;
				
				// Find possible parent category
				if ($category->parent_id)
				{
					$parent_category = $this->category_m->get($category->parent_id);
					
					if ($parent_category)
					{
						$this->parent_category = CMS::$front_data->parent_category = $parent_category;
					}
				}
				
				return true;
			}
		}
		
		return false;
		
	} // end _is_category()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _preview()
	{
		if (CMS::$uri_segment[2] == 'show')
		{
			// Get entry ID's
			$id   =    (int) @CMS::$uri_segment[3];
			$tiny = (string) @CMS::$uri_segment[4];
			
			// Decode tiny ID
			$tiny_id = Tinyo::reverseTiny($tiny);
			
			// ID's need to match
			if ($id == $tiny_id)
			{
				CMS::$front_data->entry = $entry = $this->entry_m->get_extended_with_fields($id);
				
				if ($entry)
				{
					// Get the channel
					$channel = $this->channel_m->get($entry->channel_id);
					
					// And find the template
					if ($this->_template_exists('preview/'.$channel->slug_singular))
					{
						$this->load_view = 'preview/'.$channel->slug_singular;
					}
					else
					{
						$this->load_view = 'preview/entry';
					}
					
					return true;
				}
			}
		}
		
		return false;

	} // _preview()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _template_exists($name = null)
	{
		// Set short theme paths ;)
		$template_path = reduce_double_slashes(CMS::$current_theme_abs_path.'/'.$name.'.php');
		
		// Return
		return file_exists($template_path);
		
	} // end _template_exists()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end KR_Request


/* End of file kr_request.php */