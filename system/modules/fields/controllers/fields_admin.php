<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Fields Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.3
 */

class Fields_admin extends Backend {
	
	private $_form_fields;
	protected $fields;
	protected $field_groups;
	
	
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
		$this->config->load('fields');
		$this->load->model(array(
			 'field_m'
			,'field_group_m'
			,'channels/channel_m'
		));
		$this->load->library('content/publisher');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		$channels = Backend::$channels;
		
		foreach ($channels as $channel_key=>$channel) {
			// Then get all field groups for panel
			$groups = $this->field_group_m->order_by('order_key')->get_many_by('channel_id', $channel->id);
			
			// Add Ungrouped
			$groups[] = new stdClass();
			$groups[count($groups)-1]->id 			= 1;
			$groups[count($groups)-1]->parent_id 	= NULL;
			$groups[count($groups)-1]->channel_id 	= $channel->id;
			$groups[count($groups)-1]->title 		= 'Default';
			$groups[count($groups)-1]->type 		= 'default_group';
			$groups[count($groups)-1]->order_key 	= 0;
			
			// Now add all the field into the groups
			foreach ($groups as $group_key=>$group) {
				$groups[$group_key]->fields = array();
				
				// Get all fields for current group
				$fields = $this->field_m->order_by('order_key')->get_many_by(array(
					 'channel_id' 	=> $channel->id
					,'group_id' 	=> $group->id
					,'status !=' 	=> 'trashed'
				));
				
				$groups[$group_key]->fields = $fields;
				
			} // end foreach
			
			// Order groups
			osort($groups, 'order_key', 'ASC');
			
			$channels[$channel_key]->field_groups = array();
			$channels[$channel_key]->field_groups = $groups;
			
		} // end foreach
		
		// Add sidebars
		//Backend::$data->aside_left  = 'sidebar_fields';
		//Backend::$data->aside_right = 'sidebar_channel_nav';
		
		// Pass all data to view
		Backend::$data->channels = $channels;
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function add($channel_id = null, $group_id = null)
	{
		if ($channel_id and $group_id) {
			// Get channel and field group
			$channel = $this->channel_m->get($channel_id);
			$group   = $this->field_group_m->get($group_id);
			
			// Get all channels
			$channels = Backend::$data->channels = $this->channel_m->order_by('title')->get_all();
			
			// Title
			Backend::set_title('Add field to <i>"'.$channel->title.'"</i> / group <i>"'.$group->title.'"</i>');
			
			// Prepare form
			$form = new Form();
			$form->open()
			     ->fieldset()
			     ->text('title',           'Name',            'required', null, 'autofocus=')
			     ->text('slug',            'Slug',            'required', null)
			     ->select('type', $this->config->item('types', 'fields'), 'Type')
			     ->text('extra_options',   'Options')
			     ->text('rules',           'Validation rules')
			     ->text('order_key',       'Order key',       null, 1)
			     ->html('<p>')
				     ->submit('submit', 'Save')
			     ->html('<p>')
			     ;
			
			// Form to view
			Backend::$data->form   = $form->get();
			Backend::$data->errors = $form->errors;
			
			// Run actions if valid
			if ($form->valid) {
				// Prepare data
				$data = elements(array('title', 'slug', 'type', 'extra_options', 'rules', 'order_key'), $this->input->post());
				$data['channel_id'] = $channel_id;
				$data['group_id']   = $group_id;
				$data['type'] 		= $data['type'][0];
				
				// Save it
				$id = $this->field_m->insert($data);
				
				// Update all publish fields
				$this->publisher->update_tables();
				
				// Set notice and redirect
				Notice::add('Field added.');
				admin_redirect('fields/edit/'.$id);
				
			} // end if
			
		} // end if
		
	} // end add()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function edit($id = null)
	{
		// Get field
		$field = Backend::$data->field = $this->field_m->get($id);
		
		// Get channel and field groups
		$channel = Backend::$data->channel = $this->channel_m->get($field->channel_id);
		$group   = Backend::$data->group   = $this->field_group_m->get($field->group_id);
		
		// Get all channels and fields
		$channels = Backend::$data->channels = $this->channel_m->order_by('title')->get_all();
		$fields = Backend::$data->fields = $this->field_m->get_many_by('channel_id', $channel->id);
		
		// Title
		Backend::set_title('Edit field <i>"'.$field->title.'"</i> in <i>"'.$channel->title.'"</i>');
		
		// Prepare form
		$form = new Form();
		$form->open()
		     ->fieldset()
		     ->text('title',           'Name',             'required', $field->title, 'autofocus=')
		     ->text('slug',            'Slug',             'required', $field->slug)
		     ->select('type', $this->config->item('types', 'fields'), 'Type', $field->type)
		     ->text('extra_options',   'Options',          null,       $field->extra_options)
		     ->text('rules',           'Validation rules', null,       $field->rules)
		     ->text('order_key',       'Order key',        null,       $field->order_key)
		     ->html('<p>')
			     ->submit('submit', 'Save')
		     ->html('<p>')
		     ;
		
		// Form to view
		Backend::$data->form   = $form->get();
		Backend::$data->errors = $form->errors;
		
		// Run actions if valid
		if ($form->valid) {
			// Prepare data
			$data = elements(array('title', 'slug', 'type', 'extra_options', 'rules', 'order_key'), $this->input->post());
			$data['type'] 		= $data['type'][0];
			
			// Save it
			$this->field_m->update($id, $data);
			
			// Update all publish fields
			$this->publisher->update_tables();
			
			// Set notice and redirect
			Notice::add('Field "'.$data['title'].'" saved.');
			admin_redirect('fields/edit/'.$id);
			
		} // end if
		
	} // end edit()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Fields_admin


/* End of file fields_admin.php */