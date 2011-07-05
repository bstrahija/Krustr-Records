<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Content Admin Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Content_admin extends Backend {
	
	protected $channel;
	protected $fields;
	protected $field_groups;
	protected $categories;
	protected $category_tree;
	public static $form;
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		
		// Restricted
		Auth::restrict('editor');
		
		// Load some resurces
		$this->load->library(array('user_agent', 'publisher', 'textile'));
		$this->load->model(array(
			'content/entry_m',
			'content/entry_category_m',
			'content/entry_revision_m',
			'content/entry_relation_m',
			'content/entry_meta_tag_m',
			'fields/field_m',
			'fields/field_group_m',
			'fields/field_content_m',
			'channels/channel_m',
			'categories/category_m',
			'galleries/gallery_m',
			'galleries/gallery_image_m',
		));
		
		// ----------------
		
		// Get channel
		if ( ! $this->channel = $this->channel_m->get_by('slug', $this->uri->segment(4)) ) {
			$this->channel = $this->channel_m->get_by('slug_singular', $this->uri->segment(4));
		} // end if
		Backend::$data->channel = $this->channel;
		
		// ----------------
		
		// Set navigation mark
		$this->set_nav_mark('content');
		if (isset($this->channel) and $this->channel) {
			$this->set_nav_mark($this->channel->slug, 2);
		} // end if
		
		// ----------------
		
		// Get fields and field groups
		if ($this->channel) {
			$this->field_groups = $this->field_group_m->get_by_channel($this->channel->id);
			
			// Now the fields
			$this->fields = $this->field_m->order_by('order_key')->active()->get_many_by('channel_id', $this->channel->id);
			
			// Add the fields to the groups list
			Backend::$data->field_groups = $this->field_groups = $this->field_group_m->add_fields($this->field_groups, $this->fields);
			
		} // end if
		
		// ----------------
		
		// Get channel categories
		if ($this->channel) {
			$this->categories    = Backend::$data->categories    = $this->category_m->get_many_by(array('channel_id'=>$this->channel->id));
			$this->category_tree = Backend::$data->category_tree = entry_tree($this->categories);
		} // end if
		
		// ----------------
		
		// Get all entries if channel is of "page" type.
		// This is used for the selectbox when picking a parent entry
		if ($this->channel and $this->channel->data_type == 'page') {
			$this->_page_entries = $this->entry_m->order_by('order_key')->order_by('title')->get_many_by(array(
				 'channel'		=> $this->channel->slug
				,'status !=' 	=>'trashed'
			));
			Backend::$data->page_entries    = $this->_page_entries;
			Backend::$data->page_entry_tree = entry_tree($this->_page_entries);
			
		} // end if
		
		// ----------------
		
		// Start the form
		$this->form = new Form;
		$this->form->open(current_url(), 'content-form', 'class="content-form"');
		
	} //end __contruct()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !===> Content methods */
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		admin_redirect('content/all');
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function all()
	{
		// Get the view
		if ($this->channel->layout_all) $this->view = 'all'.$this->channel->layout_all;
		else 							$this->view = 'all';
		
		
		// Setup limit based on content type
		if ($this->channel->data_type == 'page') $default_limit = 99999;
		else                                     $default_limit = 50;
		
		// Filter limit
		if ( ! (int) $this->input->get('filter_limit')) $this->db->limit($default_limit);
		else                                            $this->db->limit((int) $this->input->get('filter_limit'));
		
		// Filter keywords
		if ((string) trim($this->input->get('filter_keywords'))) {
			$this->db->like('entries.title', (string) trim($this->input->get('filter_keywords')));
		} // end if
		
		// Filter dates
		if ((int) $this->input->get('filter_before')) {
			$this->db->where('entries.published_at <=', (int) $this->input->get('filter_before'));
		} // end if
		if ((int) $this->input->get('filter_after')) {
			$this->db->where('entries.published_at >=', (int) $this->input->get('filter_after'));
		} // end if
		
		
		// Filter status
		if ((string) $this->input->get('filter_status'))
		{
			if ($this->input->get('filter_status') == 'published-draft')
			{
				$this->db->where('entries.status !=', 'trashed');
			}
			elseif ($this->input->get('filter_status') != 'all')
			{
				$this->db->where('entries.status', $this->input->get('filter_status'));
			}
		}
		else
		{
			$this->db->where('entries.status !=', 'trashed');
		}
		
		
		// Filter categories
		if ((int) $this->input->get('filter_category')) {
			$this->db->join("entry_category AS ec", "ec.entry_id = entries.id", "left");
			$this->db->where("ec.category_id", (int) $this->input->get('filter_category'));
		} // end if
		

		
		// Get channel entries with category data
		if ($this->channel->data_type == 'page') {
			Backend::$data->entries = $this->entry_m->order_by('order_key')
			                                        ->order_by('title')
			                                        ->get_many_extended(array('channel'=>$this->channel->slug), true);
		}
		else {
			Backend::$data->entries = $this->entry_m->order_by('created_at', 'DESC')
			                                        ->order_by('published_at', 'DESC')
			                                        ->get_many_extended(array('channel'=>$this->channel->slug), true);
		} // end if
		
		
		// Ajax request means no layout
		if ($this->input->is_ajax_request())
		{
			$this->layout = false;
		}
		
	} // end all()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function entry_info($id = null)
	{
		// Get entry
		$entry = Backend::$data->entry = $this->entry_m->get_extended($id);
		
		// Last changed by
		$user_change_id = $entry->user_change_id;
		if ( ! $user_change_id) $user_change_id = $entry->user_id;
		
		// Get user that last changed the entry
		$user_change = Backend::$data->user_change = $this->user_m->get_extended($user_change_id);
		
	} // end entry_info()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function add($channel = null)
	{
		// Set no categories
		Backend::$data->in_categories = null;
		
		// Default fields
		$this->_default_fields();
		
		// Channel fields
		$this->_channel_fields();
		
		// Submit
		$this->form->fieldset('Actions', 'class="actions"')
		           ->submit('submit', 'Submit');
		
		// Preselect parent
		if ($this->uri->segment(5) == 'child' and $this->uri->segment(6)) {
			Backend::$data->child_of_id = $this->uri->segment(6);
		} // end if
		
		// Form to view
		Backend::$data->form   = $this->form->get();
		Backend::$data->errors = $this->form->errors;
		
		// Run actions if valid
		if ($this->form->valid) {
			$db_default_data = elements(array('title', 'body', 'summary', 'slug', 'parent_id', 'user_id', 'channel'), $this->input->post(), null);
			$db_default_data['channel_id'] = (int) $this->channel->id;
			$db_default_data['user_id']    = $db_default_data['user_change_id'] = (int) $db_default_data['user_id'];
			if ( ! (int) $db_default_data['parent_id']) $db_default_data['parent_id'] = null;
			else                                        $db_default_data['parent_id'] = (int) $db_default_data['parent_id'];
			
			// ----------------
			
			// Check if we should publish the entry
			if ($this->input->post('status') == 'published') {
				$db_default_data['status'] = 'published';
				// Set publish date and time
				if ($this->input->post('published_at'))          $db_default_data['published_at'] = jqdatetime_to_unix($this->input->post('published_at'));
				else                                             $db_default_data['published_at'] = time();
			}
			else {
				$db_default_data['status'] = 'draft';
			} // end if
			
			// ----------------
			
			// Set slug if none is set
			if ( ! trim($db_default_data['slug']))           $db_default_data['slug'] = url_title(strtolower($db_default_data['title']));
			
			// ----------------
			
			// Rich editor format
			if ($this->config->item('rich_editor')     == 'textile')  $db_default_data['edit_format'] = 'textile';
			elseif ($this->config->item('rich_editor') == 'markdown') $db_default_data['edit_format'] = 'markdown';
			elseif ($this->config->item('rich_editor') == 'jwysiwyg') $db_default_data['edit_format'] = 'html';
			elseif ($this->config->item('rich_editor') == 'ckeditor') $db_default_data['edit_format'] = 'html';
			elseif ($this->config->item('rich_editor') == 'tinymce')  $db_default_data['edit_format'] = 'html';
			else                                                      $db_default_data['edit_format'] = 'text';
			
			// ----------------
			
			// Save entry
			if ($this->config->item('multilang')) {
				$db_default_data_empty = $db_default_data;
				
				// Empty data for translations
				$db_default_data_empty['title']   = '-';
				$db_default_data_empty['body']    = '-';
				$db_default_data_empty['summary'] = '-';
				
				// First we need to save to the default table
				$this->entry_m->clear_lang();
				if (LANG == KR_LANG) $id = $this->entry_m->insert($db_default_data);
				else                 $id = $this->entry_m->insert($db_default_data_empty);
				
				// Add the ID's
				$db_default_data_empty['id'] = $id;
				$db_default_data['id']       = $id;
				
				// And then we save the translations
				foreach ($this->config->item('langs') as $key=>$l) {
					$this->entry_m->set_lang($key);
					if ($key == LANG) $this->entry_m->insert($db_default_data);
					else              $this->entry_m->insert($db_default_data_empty);
				} // end foreach
				
				// Reset lang
				$this->entry_m->clear_lang();
				
			}
			else {
				$this->entry_m->clear_lang();
				$id = $this->entry_m->insert($db_default_data);
			} // end if
			
			// Save custom field content
			$this->_save_channel_field_content($id);
			
			// Update picked categories
			$this->_update_entry_categories($id, $this->input->post('in_categories'));
			
			// Publish if necessary
			if ($this->input->post('status') == 'published') $this->publisher->publish($id);
			
			// ----------------
			
			// Redirect
			Notice::add('"'.$this->input->post('title').'" saved to "'.$this->channel->title.'".');
			admin_redirect('content/edit/'.$this->channel->slug_singular.'/'.$id);
			
		} // end if
		
	} // end add()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function edit($channel = null, $id = null)
	{
		// Get entry
		if ($this->config->item('multilang')) $this->entry_m->set_lang(LANG);
		$entry = $this->entry = Backend::$data->entry = $this->entry_m->get_extended_with_fields((int) $id);
		
		// Could be that the entry doesn't exist as a translation so we need to create it
		if ($this->is_translation() and ! $entry) {
			$this->_create_translations($id);
			if ($this->config->item('multilang')) $this->entry_m->set_lang(LANG);
			$entry = $this->entry = Backend::$data->entry = $this->entry_m->get_extended_with_fields((int) $id);
		} // end if
		
		// Get entry meta data
		$entry_meta_tags        = $this->entry_meta_tag_m->get_by(array('entry_id'=>$id, 'lang'=>LANG));
		$this->entry->meta_tags = $entry_meta_tags;
		
		// Get revisions
		$user_change_id                         = $entry->user_change_id;
		if ( ! $user_change_id) $user_change_id = $entry->user_id;
		Backend::$data->current_revision_user   = get_user($user_change_id);
		Backend::$data->revisions               = $this->entry_revision_m->get_list($id);
		
		// Title
		$this->set_title('Edit '.$this->channel->title_singular.' <i>"'.character_limiter($entry->title, 30).'"</i>');
		
		// Get categories that entry is in
		$in_categories = $this->entry_category_m->get_many_by('entry_id', $id);
		Backend::$data->in_categories = $in_categories;
		
		// Default fields
		$this->_default_fields($id);
		
		// Channel fields
		$this->_channel_fields($id);
		
		// Submit
		$this->form->fieldset('Actions', 'class="actions"')
		           ->submit('submit', 'Submit');
		
		// Form to view
		Backend::$data->form   = $this->form->get();
		Backend::$data->errors = $this->form->errors;
		
		// Run actions if valid
		if ($this->form->valid) {
			$this->save_revision($id);
			
			$db_default_data = elements(array('title', 'body', 'summary', 'slug', 'parent_id', 'user_id', 'channel'), $this->input->post(), null);
			$db_default_data['channel_id']     = (int) $this->channel->id;
			$db_default_data['user_change_id'] = (int) $db_default_data['user_id'];
			if ( ! (int) $db_default_data['parent_id']) $db_default_data['parent_id'] = null;
			else                                        $db_default_data['parent_id'] = (int) $db_default_data['parent_id'];
			
			// ----------------
			
			$db_default_data['body'] = cleanup_html($db_default_data['body']);
			
			// ----------------
			
			// Check if we should publish the entry
			if ($this->input->post('status') == 'published') $db_default_data['status'] = 'published';
			else                                             $db_default_data['status'] = 'draft';
			
			// ----------------
			
			// Set publish date and time
			if ($this->input->post('published_at'))          $db_default_data['published_at'] = jqdatetime_to_unix($this->input->post('published_at'));
			else                                             $db_default_data['published_at'] = now();
			
			// ----------------
			
			// Set slug if none is set
			if ( ! trim($db_default_data['slug']))           $db_default_data['slug'] = url_title(strtolower($db_default_data['title']));
			
			// ----------------
			
			// Rich editor format
			if ($this->config->item('rich_editor')     == 'textile')  $db_default_data['edit_format'] = 'textile';
			elseif ($this->config->item('rich_editor') == 'markdown') $db_default_data['edit_format'] = 'markdown';
			elseif ($this->config->item('rich_editor') == 'jwysiwyg') $db_default_data['edit_format'] = 'html';
			elseif ($this->config->item('rich_editor') == 'ckeditor') $db_default_data['edit_format'] = 'html';
			elseif ($this->config->item('rich_editor') == 'tinymce')  $db_default_data['edit_format'] = 'html';
			else                                                      $db_default_data['edit_format'] = 'text';
			
			// ----------------
			
			// Save entry
			if ($this->config->item('multilang')) {
				$this->entry_m->set_lang(LANG)->update($id, $db_default_data);
				if (LANG == KR_LANG) {
					$this->entry_m->clear_lang()->update($id, $db_default_data);
				} // end if
				$this->entry_m->set_lang(LANG);
			}
			else {
				$this->entry_m->update($id, $db_default_data);
			} // end if
			
			// ----------------
			
			// Save for all languages if it doesn't exist
			$this->_create_translations($id);
			
			// ----------------
			
			// Save meta data
			$db_meta_data = array(
				'title'       => $this->input->post('meta_title'),
				'keywords'    => $this->input->post('meta_keywords'),
				'description' => $this->input->post('meta_description'),
				'redirect'    => $this->input->post('meta_redirect'),
			);
			if ( ! $this->entry->meta_tags) {
				$db_meta_data['lang']     = LANG;
				$db_meta_data['entry_id'] = $id;
				$this->entry_meta_tag_m->insert($db_meta_data);
			}
			else {
				$this->entry_meta_tag_m->update_by(array('entry_id'=>$id, 'lang'=>LANG), $db_meta_data);
			} // end if
			
			// ----------------
			
			// Save custom field content
			$this->_save_channel_field_content($id);
			
			// Update picked categories
			$this->_update_entry_categories($id, $this->input->post('in_categories'));
			
			// Publish or unpublish if necessary
			if ($this->input->post('status') == 'published') $this->publisher->publish($id);
			else                                             $this->publisher->unpublish($id);
			
			// Redirect
			$fg = ''; // Field group
			if ($this->input->post('active_field_group')) $fg = '#'.$this->input->post('active_field_group');
			Notice::add('The entry "'.$this->input->post('title').'" was saved to the "'.$this->channel->title.'" channel.');
			admin_redirect('content/edit/'.$this->channel->slug_singular.'/'.$id.$fg);
			
		} // end if
		
	} // end edit()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _create_translations($id = null)
	{
		if ($id) {
			$this->entry_m->clear_lang();
			$entry         = $this->entry_m->get($id);
			$default_entry = $this->entry_m->get($id); // Used for default language, other will be blanked out
			
			// When translations are created content data is left blank
			$entry->title   = '-';
			$entry->body    = '-';
			$entry->summary = '-';
			$entry->image   = '';
			
			// Only if multilang is enabled
			if ($this->config->item('multilang')) {
				foreach ($this->config->item('langs') as $key=>$l) {
					$this->entry_m->set_lang($key);
					
					// Try to get the translation
					$translation = $this->entry_m->get($id);
					
					// If it doesn't exist create it
					if ( ! $translation) {
						if ($key == KR_LANG) $this->entry_m->insert((array) $default_entry);
						else                 $this->entry_m->insert((array) $entry);
					} // end if
				} // end foreach
			} // end if
		} // end if
		
		// Reset entry lang
		$this->entry_m->clear_lang();
		
	} // end _create_translations()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function delete($channel = null, $id = null)
	{
		// Get entry
		$entry = $this->entry_m->get_extended($id);
		
		// Remove from publish tables
		$this->publisher->unpublish($id);
		
		// And change main status
		$this->entry_m->clear_lang();
		$this->entry_m->update($id, array('status'=>'trashed'));
		
		Notice::add('Entry <strong>"'.$entry->title.'"</strong> was removed from <strong>"'.$this->channel->title.'"</strong>.');
		admin_redirect('content/all/'.$channel);
		
		
		// Title
		/*$this->set_title('Delete '.$this->channel->title_singular.' <i>"'.character_limiter($entry->title, 30).'"</i>');
		
		if ($id) {
			$form = new Form();
			$form->open()
			     ->fieldset('Are you sure?')
		     	 ->hidden('action', 'delete')
		     	 ->hidden('id', $id)
		     	 ->html("<h4>You're about to delete the entry <i>'".$entry->title."'</i> [".$entry->id."]</h4>")
			     ->html('<p class="btns">')
			     	->submit('Yes!', 'submit', 'class="confirm"')
			     	->button('No', 'cancel', 'button', 'class="btn button cancel"')
			     ->html('</p>')
			     ;
			
			// Form to view
			Backend::$data->form   = $form->get();
			Backend::$data->errors = $form->errors;
			
			// Run actions if valid
			if ($form->valid) { exit('Yes'); }
			
		} // end if*/
		
	} // end delete()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function restore($channel = null, $id = null)
	{
		$this->view = false;
		
		// Get entry
		$entry = $this->entry_m->get_extended($id);
		
		// Update status to draft
		$this->entry_m->update($id, array('status'=>'draft'));
		
		// Notice and redirect
		Notice::add('Entry <strong>"'.$entry->title.'"</strong> was restored into <strong>"'.$this->channel->title.'"</strong>.');
		admin_redirect('content/all/'.$channel);
		
	} // restore()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Save a revision
	 *
	 */
	public function save_revision($id = null)
	{
		if ($id) {
			// Get entry
			$entry         = $this->entry_m->get_extended($id);
			$entry->fields = $this->entry_m->fields($entry);
			
			// Get user change ID
			$user_change_id                         = $entry->user_change_id;
			if ( ! $user_change_id) $user_change_id = user_id();
			
			if ($entry and $this->entry_changed($entry)) {
				$db_data = array(
					 'entry_id'	=> $id
					,'user_id'	=> $user_change_id
					,'title'	=> $entry->title
					,'body'		=> $entry->body
					,'fields'	=> json_encode($entry->fields)
				);
				
				$rev_id = $this->entry_revision_m->insert($db_data);
				
				return $rev_id;
				
			} // end if
		} // end if
		
		return false;
		
	} // end save_revision()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Check if any changes were made when submiting the entry
	 *
	 */
	public function entry_changed($entry = null)
	{
		$changed = false;
		
		// Default fields
		if ($entry->title != $this->input->post('title') or $entry->body != $this->input->post('body') or $entry->summary != $this->input->post('summary')) {
			$changed = true;
		} // end if
		
		// Custom fieds
		foreach ($entry->fields as $key=>$field) {
			if ($field != $this->input->post($key)) {
				$changed = true;
			} // end if
		} // end foreach
		
		return $changed;
		
	} // end entry_changed()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function ajax_order()
	{
		$this->view = false;
		$order = 1;
		
		//echo '<pre>'; print_r($_POST); echo '</pre>';
		
		if ($this->input->is_ajax_request() and $_POST and $this->input->post('entry_id')) {
			foreach ($this->input->post('entry_id') as $id) {
				echo $order, ' :: ', $id, '<br>\n';
				$this->entry_m->update($id, array('order_key'=>$order));
				$order++;
			} // end foreach
		} // end if
		
	} // end ajax_order()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !===> Fields */
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * And now prepare channel fields and group them
	 *
	 */
	public function _channel_fields()
	{
		if ($this->field_groups) {
			foreach ($this->field_groups as $group) {
				// Start group
				$this->form->fieldset($group->title, 'id="field-group-'.$group->id.'"');
				
				// Fields
				if ((isset($group->fields) and ! empty($group->fields)) or $group->type == 'default_group' or $group->type == 'meta_tags') {
					$options = explode('|', $this->channel->extra_options);
					
					// -----------------------
					
					// Default fields
					if ($group->type == 'default_group' and $group->slug == 'default') {
						// Format body (if textiled)
						$body = @$this->entry->body;
						
						// Format summary (if textiled)
						$summary = @$this->entry->summary;
						
						$this->form->text(    'title',   'Title',    'required',  @$this->entry->title, 'class="big",autofocus,autocomplete="off",rel="title"');
						$this->form->text(    'slug',    'Slug',     null,        @$this->entry->slug,  'class="slug",autocomplete="off",rel="slug"');
						if ( ! in_array('hide_body', $options))    $this->form->textarea('body',    'Body',     null,        $body,    'class="rich",rel="body"');
						if ( ! in_array('hide_summary', $options)) $this->form->textarea('summary', 'Summary',  null,        @$this->entry->summary, 'class="rich",rel="rich"');
					} // end if
				
					// -----------------------
					
					// Meta fields
					if ($group->type == 'meta_tags' and $group->slug == 'meta') {
						$this->_meta_fields();
					} // end if
					
					// -----------------------
					
					
					$fields = @$group->fields;
					
					// Add fields to form
					if ($fields) {
						foreach ($fields as $field) {
							// Get field options
							$field_options = explode('|', $field->extra_options);
							
							// Field content
							$content = $field->content = @$this->entry->{'f_'.$field->slug};
							
							
							// -----------------------
							
							// Textarea
							if ($field->type == 'text' and in_array('multiline', $field_options)) {
								$this->_field_textarea($field, $content);
							}
							
							// -----------------------
							
							// Date
							elseif ($field->type == 'date') {
								$this->form->text($field->slug, $field->title, $field->rules, ($content) ? date('Y/m/d', $content) : date('Y/m/d'), array('class'=>'date-picker', 'readonly'=>'readonly', 'autocomplete'=>'off'));
							}
							
							// -----------------------
							
							// URL
							elseif ($field->type == 'url') {
								$this->form->text($field->slug, $field->title, $field->rules, $content, 'data-type="url", data-help="'.urlencode($field->body).'"');
							}
							
							// -----------------------
							
							// Email
							elseif ($field->type == 'email') {
								$this->form->text($field->slug, $field->title, $field->rules.'|valid_email', $content, 'data-type="email", data-help="'.urlencode($field->body).'"');
							}
							
							// -----------------------
							
							// Image
							elseif ($field->type == 'image') {
								$this->_field_image($field, $content);
							}
							
							// -----------------------
							
							// Gallery
							elseif ($field->type == 'gallery') {
								$this->_field_gallery($field, $content);
							}
							
							// -----------------------
							
							// Video
							elseif ($field->type == 'video') {
								$this->_field_video($field, $content);
							}
							
							// -----------------------
							
							// Relation
							elseif ($field->type == 'relation') {
								if (@$this->entry) {
									$this->form->html('<div class="row relation"><label class="left">'.$field->title.'</label>'.$this->_relations($this->entry->id, $field).'</div>');
								}
								else {
									$this->form->html('<div class="row relation"><label class="left">'.$field->title.'</label>'.$this->_relations(null, $field).'</div>');
								} // end if
							}
							
							// -----------------------
							
							// Simple text field
							else {
								$this->form->text($field->slug, $field->title, $field->rules, $content, array('autocomplete'=>'off', 'data-help'=>urlencode($field->body)));
							
							} // end if
							
							// -----------------------
							
						} // end foreach
					} // end if
				}
				else {
					if ($group->type != 'meta_tags' and $group->slug != 'meta') {
						$this->form->html('<p class="nothing-found">No fields available in this group. <a href="'.admin_url('fields/add/'.$this->channel->id.'/'.$group->id).'">Add a field</a>?</p>');
					} // end if
					
				} // end if
			} // end foreach
		} // end if
		
	} // end _channel_fields()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Prepare default fields for channel
	 *
	 */
	private function _default_fields()
	{
		if (@$this->entry) $this->form->hidden('entry_id', $this->entry->id);
		
		
		$this->form->hidden('status',             (@$this->entry) ? $this->entry->status : 'draft')
		           ->hidden('parent_id',          (@$this->entry) ? $this->entry->parent_id : null)
		           ->hidden('user_id',            user_id())
		           ->hidden('in_categories',      $this->_in_category_ids(Backend::$data->in_categories))
		           ->hidden('active_field_group', null)
		           ->hidden('published_at',       (@$this->entry) ? date('Y/m/d H:i', $this->entry->published_at) : null)
		           ->hidden('channel',            $this->channel->slug)
		; // end form

		
	} // end _default_fields()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _meta_fields()
	{
		$this->form->text('meta_title',           'Meta Title',       null, @$this->entry->meta_tags->title)
		           ->text('meta_keywords',        'Meta Keywords',    null, @$this->entry->meta_tags->keywords)
		           ->textarea('meta_description', 'Meta Description', null, @$this->entry->meta_tags->description)
		           ->text('meta_redirect',        'Redirect',         null, @$this->entry->meta_tags->redirect)
		; // end form
		
	} // end _meta_fields()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _save_channel_field_content($entry_id = null)
	{
		// Set language for fields
		/*if ($this->config->item('multilang')) {
			$this->field_content_m->_table
		} // end if*/
		
		if ($this->fields and $entry_id) {
			foreach ($this->fields as $field) {
				
				// -----------------------
				
				// A field matrix is stored differently
				if ($field->type == 'field_matrix') {
					
				}
				
				// -----------------------
				
				// Relations are stored differently
				elseif ($field->type == 'relation') {
					$submited     = $this->input->post('relation'); $submited = $submited[$field->id];
					$rel_entry_id = $entry_id;
					
					// First delete all relations so we can make the update
					$this->entry_relation_m->delete_by('entry_id', $rel_entry_id);
					
					// And now add the new relations
					if (is_array($submited)) : foreach ($submited as $related_id) :
						$this->entry_relation_m->insert(array(
							 'entry_id' 	=> $rel_entry_id
							,'related_id' 	=> $related_id
							,'field_id' 	=> $field->id
							,'lang'         => LANG
						));
					endforeach; endif;
					
				}
				
				// -----------------------
				
				// Images
				elseif ($field->type == 'image')
				{
					$img_title       = $this->input->post('image-14-title');
					$img_description = $this->input->post('image-14-description');
					
					// Save it to the database
					$this->field_content_m->update_by(
						array(
							'entry_id' => $entry_id,
							'field_id' => $field->id,
						),
						array(
							'title'       => $img_title,
							'description' => $img_description,
						)
					);
				}
				
				// -----------------------
				
				// Videos
				elseif ($field->type == 'video') {
					// Get field if it already exists in DB
					$existing = $this->field_content_m->get_by(array(
						 'field_id' => $field->id
						,'entry_id' => $entry_id
					));
					
					// Check submitted value
					$submitted_content = $this->input->post($field->slug);
					
					// Update or insert
					if ($existing) {
						$this->field_content_m->update_by(
							 array(
								 'field_id' => $field->id
								,'entry_id' => $entry_id
							)
							,array(
								'body' 	=> $submitted_content
							)
						); // end update()
					}
						
					else {
						$this->field_content_m->insert(array(
							 'field_id' => $field->id
							,'entry_id' => $entry_id
							,'body' 	=> $submitted_content
							,'lang'     => LANG
						)); // end insert()
						
					} // end if
				}
				
				// -----------------------
				
				// Gallery field
				elseif ($field->type == 'gallery')
				{
					// Title and description
					$title       = $this->input->post('gallery-'.$field->id.'-title');
					$description = $this->input->post('gallery-'.$field->id.'-description');
					
					// Get field if it already exists in DB
					$existing = $this->field_content_m->get_by(array(
						'field_id' => $field->id,
						'entry_id' => $entry_id,
					));
					
					if($existing)
					{
						$existing_gallery = $this->gallery_m->get((int) $existing->body);
						
						if ($existing_gallery)
						{
							$this->gallery_m->update($existing_gallery->id, array('title'=>$title, 'body'=>$description));
						}
						else
						{
							$this->gallery_m->insert(array('entry_id'=>$entry_id,'field_id'=>$field->id,'title'=>$title, 'body'=>$description));
						}
					}
				}
				
				// -----------------------
				
				// Normal fields
				elseif ($field->type != 'image' && $field->type != 'gallery' && $field->type != 'video' && $field->type != 'file') {
					// Get field if it already exists in DB
					$existing = $this->field_content_m->get_by(array(
						 'field_id' => $field->id
						,'entry_id' => $entry_id
						,'lang'     => LANG
					));
					
					// Check submitted value
					$submitted_content = $this->input->post($field->slug);
					
					// Convert date to unix format
					if ($field->type == 'date')
					{
						$submitted_content = jqdate_to_unix($submitted_content);
					}
					
					// Update or insert
					if ($existing) {
						$this->field_content_m->update_by(
							 array(
								 'field_id' => $field->id
								,'entry_id' => $entry_id
								,'lang'     => LANG
							)
							,array(
								'body' 	=> $submitted_content
							)
						); // end update()
					}
						
					else {
						$this->field_content_m->insert(array(
							 'field_id' => $field->id
							,'entry_id' => $entry_id
							,'body' 	=> $submitted_content
							,'lang'     => LANG
						)); // end insert()
						
					} // end if
				
				} // end if
				
			} // end foreach
		} // end if
		
	} // end _save_channel_field_content()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _field_textarea($field = null, $content = null)
	{
		if ($field)
		{
			$field_options = explode('|', $field->extra_options);
			
			// WYSIWYG or plain text
			if (in_array('rich', $field_options)) $this->form->textarea($field->slug, $field->title, $field->rules, $content, 'class="rich", data-help="'.urlencode($field->body).'"');
			else                                  $this->form->textarea($field->slug, $field->title, $field->rules, $content, 'class="plain", data-help="'.urlencode($field->body).'"');
		}
		
	} // end _field_textarea()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _field_image($field = null, $content = null)
	{
		if ($field) {
			$this->form->html('<div class="row image-field">')
			           ->html('<label>'.$field->title.'</label>')
			           ;
			
			// Get entire field content
			$field_content = $this->field_content_m->get_by(array(
				'entry_id' => @$this->entry->id,
				'field_id' => $field->id,
			));
			
			// If not saved
			if ( ! @$this->entry) {
				$this->form->html('<span class="manager image-manager">Save entry first</span>');
			}
			else {
				$this->form->html( $this->load->view('content/widgets/uploader_image', array('field'=>$field, 'content'=>$content, 'field_id'=>$field->id, 'entry_id'=>$this->entry->id, 'field_content'=>$field_content), true) );
			} // end if
			
			// Close it
			$this->form->html('</div>');
			
		} // end if
		
	} // end _field_image()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _field_gallery($field = null, $content = null)
	{
		if ($field) {
			$this->form->html('<div class="row image-field">')
			           ->html('<label>'.$field->title.'</label>')
			           ;
			
			// If not saved
			if ( ! @$this->entry) {
				$this->form->html('<span class="manager image-manager">Save entry first</span>');
			}
			else {
				// Get gallery and images
				$gallery        = $this->gallery_m->get((int) $content);
				$gallery_images = $this->gallery_image_m->get_many_by('gallery_id', (int) @$gallery->id);
				
				// Load widget
				$this->form->html( $this->load->view('content/widgets/uploader_gallery', array('field'=>$field, 'content'=>$content, 'gallery'=>$gallery, 'gallery_images'=>$gallery_images, 'field_id'=>$field->id, 'entry_id'=>$this->entry->id), true) );
				
			} // end if
			
			// Close it
			$this->form->html('</div>');
			
		} // end if
		
	} // end _field_gallery()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function _field_video($field = null, $content = null)
	{
		$this->form->html('<div class="row video-field">')
		           ->html('<label>'.$field->title.'</label>')
		           ;
		
		$this->form->html( $this->load->view('content/widgets/video_field', array('field'=>$field, 'content'=>$content), true) );
		$this->form->text($field->slug, null, $field->rules, $content, array('autocomplete'=>'off'));
		
		// Close it
		$this->form->html('</div>');
		
	} // end _field_video()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !===> Relations */
	/* ------------------------------------------------------------------------------------------ */
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _relations($entry_id = null, $field = null)
	{
		$f_entry_id = $entry_id;
		
		// First get extra options for field
		$options = array();
		$tmp_options = explode('|', $field->extra_options);
		foreach ($tmp_options as $key=>$option) :
			$opt = explode(':', $option);
			if (@$opt[1]) 	$options[$opt[0]] = $opt[1];
			else			$options[$opt[0]] = true;
		endforeach;
		
		
		// Get all existing relations
		$relation_ids = array();
		$relations = $this->entry_relation_m->get_many_by(array(
			'entry_id'=>$f_entry_id
		));
		if ($relations) :
			foreach ($relations as $key=>$relation) :
				$relation_ids[] = $relation->related_id;
				$this->entry_m->clear_lang();
				$entry_data = $this->entry_m->get($relation->related_id);
				$relations[$key]->entry_data = $entry_data;
			endforeach;
		endif;
		
		
		// Restrict relations only to 1 channel
		if (isset($options['channel_id'])) :
			$data['channel_id'] = $options['channel_id'];
			
			// Get the channel
			$channel = $this->channel_m->get($data['channel_id']);
			
			// Get entries
			$this->entry_m->clear_lang();
			$entries = $this->entry_m->order_by('title')->get_many_by(array(
				 'status !=' 	=> 'trashed'
				,'channel_id' 	=> $channel->id
			));
			
		// Get all entries
		else :
			$this->entry_m->clear_lang();
			$entries = $this->entry_m->order_by('title')->get_many_by(array('status' 	=> 'published'));
		endif;
		
		// Prepare data
		$data = array(
			 'entry_id' 	=> $f_entry_id
			,'relations' 	=> $relations
			,'relation_ids' => $relation_ids
			,'entries' 		=> $entries
			,'field' 		=> $field
		);
		
		
		
		// Get the view
		return $this->load->view('fields/relations', $data, true);
		
	} //end _relations()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function add_relation()
	{
		// Set framed layout
		$this->layout = 'layouts/framed';
		
		
	} //end add_relation()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function remove_relation($id = null)
	{
		$this->view = false;
		
		if ($id) {
			$relation = $this->entry_relation_m->get($id);
			$entry    = $this->entry_m->get($relation->entry_id);
			$channel  = $this->channel_m->get($entry->channel_id);
			$field    = $this->field_m->get($relation->field_id);
			
			// Delete id
			$this->entry_relation_m->delete($id);
			
			// Notice and redirect
			Notice::add("Relation was removed.");
			admin_redirect('content/edit/'.$channel->slug_singular.'/'.$entry->id.'#'.$field->group_id);
		} // end if
		
	} //end remove_relation()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !===> Categories */
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function _update_entry_categories($entry_id = null, $in_categories = null)
	{
		$f_entry_id = $entry_id;
		
		if ($f_entry_id) {
			// First delete all existing relations
			$this->entry_category_m->delete_by('entry_id', $f_entry_id);
			
			// And then add new relations
			$in_categories = explode(',', $in_categories);
			
			foreach ($in_categories as $category_id) {
				if ($category_id) {
					$this->entry_category_m->insert(array(
						 'entry_id' 	=> $f_entry_id
						,'category_id' 	=> $category_id
					));
				} // end if
			} // end foreach
		} // end if
		
	} //end _update_entry_categories()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Get all category ids and implode them with a comma
	 */
	private function _in_category_ids($categories = null)
	{
		if ($categories) :
			$tmp = array();
			
			foreach ($categories as $category) :
				$tmp[] = $category->category_id;
			endforeach;
			
			$categories = implode($tmp, ',');
			
			return $categories;
			
		endif;
	} //end _in_category_ids()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !===> Misc */
	/* ------------------------------------------------------------------------------------------ */
	
	
	/**
	 *
	 */
	public function sync_published($back = null)
	{
		$this->view = false;
		
		$this->publisher->update_tables();
		$this->publisher->update_admin_tables();
		
		$this->publisher->sync_published();
		
		if ($back) {
			Notice::add('All published entries are now synced with the publish tables.');
			redirect($this->agent->referrer());
		} // end if
		
	} // end sync_published()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function change_lang($lang = NULL, $hash = NULL)
	{
		$this->view = FALSE;
		
		// Change if
		change_lang($lang, $hash);
		
		// Set a message
		Notice::add("Language changed to '".$lang."'.");
		
		// And redirect back
		$redirect_to = $this->agent->referrer();
		if ($hash) $redirect_to .= '#'.$hash;
		redirect($redirect_to);
		
	} //end change_lang()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function is_translation()
	{
		if (LANG != KR_LANG) return true;
		
		return false;
		
	} // end is_translation()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function copy_from_lang($entry_id = null, $field = null, $lang = null)
	{
		$this->layout = false;
		$this->view   = false;
		
		// Language
		if ( ! $lang) $lang = KR_LANG;
		
		// Prepare data
		$data = new stdClass;
		
		// Get the entry
		$lang_entry = $this->entry_m->set_lang($lang)->get_extended_with_fields($entry_id);
		$data->entry = $lang_entry;
		
		$this->load->view('copy_from_lang', $data);
		
	} // end copy_from_lang()
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Content_admin


/* End of file content_admin.php */