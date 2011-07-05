<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Field Groups Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Groups_admin extends Backend {
	
	private $_channel_select = array();
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		
		// Set user restriction
		Auth::restrict('superadmin');
		
		// Set navigation mark
		$this->set_nav_mark('layout');
		$this->set_nav_mark('fields', 2);
		
		// Load resources
		$this->load->model('channels/channel_m');
		$this->load->model('field_m');
		$this->load->model('field_group_m');
		$this->load->library('content/publisher');
		
		// Get channels
		$channels = $this->channel_m->get_all();
		foreach ($channels as $channel) {
			$this->_channel_select[$channel->id] = $channel->title;
		} // end foreach
		
		// Set subtitle
		Backend::set_title('Field groups');
		Backend::set_data('subtitle', 'Manage content channels, field groups and fields');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		admin_redirect('fields/groups/add');
		
	} // end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function add($channel_id = null)
	{
		// Set view
		$this->view = 'add_group';
		
		// Get the channel
		$channel = Backend::$data->channel = $this->channel_m->get($channel_id);
		
		// Get all channels
		$channels = Backend::$data->channels = $this->channel_m->order_by('title')->get_all();
		
		// Title and buttons
		$this->set_title('Add Field Group To Channel <i>"'.$channel->title.'"</i>');
		
		// Create the form
		$form = new Form();
		$form->fieldset()
		     ->text  ('title',            'Name',                  'required',          null)
		     ->text  ('order_key',        'Order key',             'required|numeric',  999)
		     ->select('channel_id',       $this->_channel_select,  'Channel', $channel_id)
		     ->html('<p class="row btns">')
			     ->submit('submit',       'Submit')
			 ->html('</p>')
		     ;
		
		// Form to view
		Backend::$data->form   = $form->get();
		Backend::$data->errors = $form->errors;
		
		// Run actions if valid
		if ($form->valid) {
			$id = $this->field_group_m->insert(array(
				 'title'		=> $this->input->post('title')
				,'order_key'	=> $this->input->post('order_key')
				,'slug'			=> url_title($this->input->post('title'), 'dash', TRUE)
				,'channel_id'	=> $channel_id
			));
			
			// Update all publish fields
			$this->publisher->update_tables();
			
			// Set notice and redirect
			Notice::add('Field group added.');
			admin_redirect('fields/groups/edit/'.$channel_id.'/'.$id);
			
		} // end if
		
	} // end add_group()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function edit($channel_id = null, $id = null)
	{
		// Set view
		$this->view = 'add_group';
		
		// Get the channel
		$channel = Backend::$data->channel = $this->channel_m->get($channel_id);
		
		// Get all channels
		$channels = Backend::$data->channels = $this->channel_m->order_by('title')->get_all();
		
		// Get the group
		$group   = $this->field_group_m->get($id);
		
		// Title and buttons
		$this->set_title('Edit field group <i>"'.$group->title.'"</i>');
		
		// Create the form
		$form = new Form();
		$form->fieldset()
		     ->text  ('title',       'Name',                  'required',          $group->title)
		     ->text  ('order_key',   'Order key',             'required|numeric',  $group->order_key)
		     ->text  ('slug',        'Slug',                  'required',          $group->slug)
		     ->select('channel_id',  $this->_channel_select,  'Channel',           $group->channel_id)
		     ->html('<p class="row btns">')
			     ->submit('submit',      'Submit')
			 ->html('</p>')
		     ;
		
		// Form to view
		Backend::$data->form   = $form->get();
		Backend::$data->errors = $form->errors;
		
		// Run actions if valid
		if ($form->valid) {
			$this->field_group_m->update($id, array(
				 'title'		=> $this->input->post('title')
				,'order_key'	=> $this->input->post('order_key')
				,'slug'			=> url_title($this->input->post('slug'), 'dash', TRUE)
				,'channel_id'	=> $group->channel_id
			));
			
			// Update all publish fields
			$this->publisher->update_tables();
			
			// Set notice and redirect
			Notice::add('Field group updated.');
			admin_redirect('fields/groups/edit/'.$channel_id.'/'.$id);
			
		} // end if
		
	} // end edit()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Groups_admin


/* End of file groups_admin.php */