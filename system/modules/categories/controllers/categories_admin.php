<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Categories Admin Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Categories_admin extends Backend {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Restricted access
		Auth::restrict('editor');
		
		// Set navigation mark
		$this->set_nav_mark('content');
		$this->set_nav_mark('categories', 2);
		
		// Load resources
		$this->load->model('categories/category_m');
		$this->load->model('content/entry_m');
		$this->load->model('content/entry_category_m');
		$this->load->model('channels/channel_m');
		
		// Get all channels and categories
		$this->categories 				= $this->category_m->order_by('title')->get_many_by('status', 'active');
		$this->channels 				= $this->channel_m->order_by('order_key', 'title')->get_many_by('status', 'active');
		$this->category_tree 			= entry_tree($this->categories);
		Backend::$data->channels 		= $this->channels;
		Backend::$data->category_tree 	= $this->category_tree;
		
		// Prepare channel combobox
		$this->channel_select = array();
		foreach ($this->channels as $channel) :
			$this->channel_select[$channel->id] = $channel->title;
		endforeach;
		
		// Prepare category combobox
		$this->category_select 			= array(''=>'None');
		if ($this->category_tree) :
			foreach ($this->category_tree as $cat) :
				$this->category_select[$cat->id] = repeater('&mdash;', (int) $cat->offset).' '.$cat->title;
			endforeach;
		endif;
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		admin_redirect('categories/all');
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function all()
	{
		// Get all categories by entry type
		foreach ($this->channels as $channel) {
			Backend::$data->categories[$channel->id] = $this->category_m->get_many_by(array(
				 'channel_id' => $channel->id,
				 'status'     => 'active'
			));
			
			// Get entry count
			foreach (Backend::$data->categories[$channel->id] as $key=>$cat) {
				Backend::$data->categories[$channel->id][$key]->entry_count = $this->entry_category_m->count_by('category_id', $cat->id);
			} // end foreach
			
			// Build tree structure
			Backend::$data->category_tree[$channel->id] 	= entry_tree(Backend::$data->categories[$channel->id]);
			
		} // end foreach
		
	} // end all()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Categories_admin


/* End of file categories_admin.php */