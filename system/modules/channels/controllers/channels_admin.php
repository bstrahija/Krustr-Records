<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Channels Admin Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Channels_admin extends Backend {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Restricted access
		Auth::restrict('superadmin');
		
		// Set navigation mark
		$this->set_nav_mark('layout');
		$this->set_nav_mark('channels', 2);
		
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		// Get all channels
		$channels = Backend::$data->channels = $this->channel_m->order_by('title')->get_all();
		
	} // end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function add()
	{
		// Setup form
		$form = new Form();
		$form->open()
		     ->fieldset()
		     ->text('title',           'Name (plural)',     'required')
		     ->text('slug',            'Slug (plural)',     'required')
		     ->text('title_singular',  'Name (singular)',   'required')
		     ->text('slug_singular',   'Slug (singular)',   'required')
		     ->text('url_trigger',     'URL trigger',       'required')
		     ->text('icon',            'Icon')
		     ->textarea('body',        'Description')
		     ->select('data_type',     array('article'=>'Article', 'page'=>'Page'), 'Data type')
		     ->text('extra_options',   'Extra options')
		     ->text('relation_name',   'Relation name',     'required')
		     ->text('order_key',       'Order key',         'required', 999)
		     ->submit('Save')
		     ;
		
		// Form to view
		Backend::$data->form   = $form->get();
		Backend::$data->errors = $form->errors;
		
		// Run actions if valid
		if ($form->valid) {
			$post = elements(array('title', 'title_singular', 'slug', 'slug_singular', 'url_trigger', 'icon', 'body', 'data_type', 'extra_options', 'relation_name', 'order_key'), $this->input->post());
			
			// Prepare data type
			$post['data_type'] = $post['data_type'][0];
			
			// Save it
			$id = $this->channel_m->insert($post);
			
			// Notice and redirect
			Notice::add('Channel <b>"'.$post['title'].'"</b> saved.');
			admin_redirect('channels/edit/'.$id);
			
		} // end if
		
	} //end add()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function edit($id = null)
	{
		// Get the channel
		$channel = Backend::$data->channel = $this->channel_m->get($id);
		
		// Get all channels
		$channels = Backend::$data->channels = $this->channel_m->order_by('title')->get_all();
		
		// Setup form
		$form = new Form();
		$form->open()
		     ->fieldset()
		     ->text('title',           'Name (plural)',     'required', $channel->title)
		     ->text('slug',            'Slug (plural)',     'required', $channel->slug)
		     ->text('title_singular',  'Name (singular)',   'required', $channel->title_singular)
		     ->text('slug_singular',   'Slug (singular)',   'required', $channel->slug_singular)
		     ->text('url_trigger',     'URL trigger',       'required', $channel->url_trigger)
		     ->text('icon',            'Icon',              null,       $channel->icon)
		     ->textarea('body',        'Description',       null,       $channel->body)
		     ->select('data_type',     array('article'=>'Article', 'page'=>'Page'), 'Data type', $channel->data_type)
		     ->text('extra_options',   'Extra options',     null,       $channel->extra_options)
		     ->text('relation_name',   'Relation name',     'required', $channel->relation_name)
		     ->text('order_key',       'Order key',         'required', $channel->order_key)
		     ->submit('Save')
		     ;
		
		// Form to view
		Backend::$data->form   = $form->get();
		Backend::$data->errors = $form->errors;
		
		// Run actions if valid
		if ($form->valid) {
			$post = elements(array('title', 'title_singular', 'slug', 'slug_singular', 'url_trigger', 'icon', 'body', 'data_type', 'extra_options', 'relation_name', 'order_key'), $this->input->post());
			
			// Prepare data type
			$post['data_type'] = $post['data_type'][0];
			
			// Save it
			$this->channel_m->update($id, $post);
			
			// Notice and redirect
			Notice::add('Channel <b>"'.$post['title'].'"</b> saved.');
			admin_redirect('channels/edit/'.$id);
			
		} // end if
		
	} // end edit()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Channels_admin


/* End of file channels_admin.php */