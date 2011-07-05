<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	

/**
 * Content Upload Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Upload_admin extends Backend {
	
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
		
		// Resources
		$this->load->library('content/publisher');
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
		
		// Disable profiler
		$this->output->enable_profiler(false);
		
	} //end __contruct()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !✰ Images */
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function image($entry_id = null, $field_id = null)
	{
		// Disable template
		$this->view = false;
		
		// Get uploaded stream
		$input    = fopen("php://input", "r");
        $tmp_file = tmpfile();
        $realSize = stream_copy_to_stream($input, $tmp_file);
        fclose($input);
        
		// Get entry, field, channel
		$entry   = $this->entry_m->get_extended_with_fields($entry_id);
		$field   = $this->field_m->get($field_id);
		$channel = $this->channel_m->get($entry->channel_id);
		
		// Prepare the file path and create dir's
		$file_dir = $this->config->item('entry_path', 'uploads');
		@mkdir($file_dir, 0777);
		$file_dir = reduce_double_slashes($file_dir.'/'.$channel->slug);
		@mkdir($file_dir, 0777);
		$file_dir = reduce_double_slashes($file_dir.'/'.$entry->id);
		@mkdir($file_dir, 0777);
		$file_dir = reduce_double_slashes($file_dir.'/'.$field->id);
		@mkdir($file_dir, 0777);
		$file_name = url_title(strtolower($this->input->get('qqfile')), 'underscore');
		$file_path = reduce_double_slashes($file_dir.'/'.$file_name);
		
		// Copy file to direcotry
		$target = fopen($file_path, "w");        
		fseek($tmp_file, 0, SEEK_SET);
		stream_copy_to_stream($tmp_file, $target);
		fclose($target);
		
		// Add to database
		$existing = $this->field_content_m->get_by(array('entry_id'=>$entry->id, 'field_id'=>$field->id));
		$data = array(
			'entry_id' => $entry->id,
			'field_id' => $field->id,
			'body'     => trim($file_path, "/"),
		);
		if ($existing) $this->field_content_m->update($existing->id, $data);
		else           $this->field_content_m->insert($data);

		// Return result to uploader
		echo "{success:true}";
		die();
		
	} // image()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function preview_image($entry_id = null, $field_id = null)
	{
		// Disable template
		$this->view = false;
		
		// Get entry, field, channel
		$image   = $this->field_content_m->get_by(array(
			'entry_id' => $entry_id,
			'field_id' => $field_id,
		));
		
		// Prepare view data
		$data = array('content'=>trim($image->body, "/"));
		
		// And load the view
		$this->load->view('widgets/uploader_image_preview', $data);
		
	} // preview_image()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function remove_image($entry_id = null, $field_id = null)
	{
		// Disable template
		$this->view = false;
		
	} // remove_image()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !✰ Galleries */
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function gallery($entry_id = null, $field_id = null)
	{
		// Disable template
		$this->view = false;
		
		// Get uploaded stream
		$input    = fopen("php://input", "r");
        $tmp_file = tmpfile();
        $realSize = stream_copy_to_stream($input, $tmp_file);
        fclose($input);
        
		// Get entry, field, channel
		$entry   = $this->entry_m->get_extended_with_fields($entry_id);
		$field   = $this->field_m->get($field_id);
		$channel = $this->channel_m->get($entry->channel_id);
		
		// Try to get gallery
		$gallery = $this->gallery_m->get_by(array(
			'entry_id' => $entry->id,
			'field_id' => $field->id,
		));
		
		// Create one if it doesn't exist
		if ( ! $gallery)
		{
			$gallery_id = $this->gallery_m->insert(array(
				'entry_id' => $entry->id,
				'field_id' => $field->id,
				'type'     => 'entry-gallery',
			));
			
			$gallery = $this->gallery_m->get($gallery_id);
		}
		
		// Prepare the file path and create dir's
		$file_dir = $this->config->item('entry_gallery_path', 'uploads');
		@mkdir($file_dir, 0777);
		$file_dir = reduce_double_slashes($file_dir.'/'.$channel->slug);
		@mkdir($file_dir, 0777);
		$file_dir = reduce_double_slashes($file_dir.'/'.$entry->id);
		@mkdir($file_dir, 0777);
		$file_dir = reduce_double_slashes($file_dir.'/'.$field->id);
		@mkdir($file_dir, 0777);
		$file_name = url_title(strtolower($this->input->get('qqfile')), 'underscore');
		$file_path = reduce_double_slashes($file_dir.'/'.$file_name);
		
		// Copy file to direcotry
		$target = fopen($file_path, "w");        
		fseek($tmp_file, 0, SEEK_SET);
		stream_copy_to_stream($tmp_file, $target);
		fclose($target);
		
		// Check if image already existst in gallery
		$existing_image = $this->gallery_image_m->get_by(array
		(
			'gallery_id' => $gallery->id,
			'file_path'  => trim($file_path, "/"),
		));
		if ( ! $existing_image)
		{
			// Add to database
			$image_id = $this->gallery_image_m->insert(array
			(
				'gallery_id' => $gallery->id,
				'file_path'  => trim($file_path, "/"),
			));
		}
		
		// Write gallery ID to field content
		$existing = $this->field_content_m->get_by(array(
			'entry_id' => $entry->id,
			'field_id' => $field->id,
		));
		$data = array(
			'entry_id' => $entry->id,
			'field_id' => $field->id,
			'body'     => $gallery->id,
		);
		if ($existing) $this->field_content_m->update($existing->id, $data);
		else           $this->field_content_m->insert($data);
		
		// Return result to uploader
		echo "{success:true}";
		die();

	} // gallery()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function preview_gallery($entry_id = null, $field_id = null)
	{
		// Disable template
		$this->view = false;
		
		// Get gallery and images
		$gallery = $this->gallery_m->get_by(array(
			'entry_id' => $entry_id,
			'field_id' => $field_id,
		));
		$images = $this->gallery_image_m->get_many_by('gallery_id', $gallery->id);
		
		// Prepare view data
		$data = array('gallery_images'=>$images, 'field_id'=>$field_id, 'entry_id'=>$entry_id);
		
		// And load the view
		$this->load->view('widgets/uploader_gallery_preview', $data);
		
	} // preview_gallery()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function edit_gallery_image($image_id = null)
	{
		// Disable template
		$this->view = false;
		
		// Get the image, gallery and entry
		$image   = $this->gallery_image_m->get($image_id);
		$gallery = $this->gallery_m->get($image->gallery_id);
		$entry   = $this->entry_m->get($gallery->entry_id);
		$field   = $this->field_m->get($gallery->field_id);
		$channel = $this->channel_m->get($entry->channel_id);
		
		if ($image and $this->input->post())
		{
			// Save it
			$this->gallery_image_m->update($image->id, array(
				'title' => $this->input->post('title'),
			));
			
			// Republish
			$this->publisher->publish($entry->id);
			
			// Amnd redirect
			admin_redirect('content/edit/'.$channel->slug.'/'.$entry->id.'#'.$field->group_id);
		}
		
		// And load the view
		$this->load->view('widgets/edit_gallery_image', array('image'=>$image));
		
	} // edit_gallery_image()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function remove_gallery_image($image_id = null)
	{
		// Disable template
		$this->view = false;
		
		// Get the image, gallery and entry
		$image   = $this->gallery_image_m->get($image_id);
		$gallery = $this->gallery_m->get($image->gallery_id);
		$entry   = $this->entry_m->get($gallery->entry_id);
		$field   = $this->field_m->get($gallery->field_id);
		$channel = $this->channel_m->get($entry->channel_id);
		
		// Delete the file
		@unlink($image->file_path);
		
		// Delete from DB
		$this->gallery_image_m->delete($image_id);
		
		// Treat ajax request differently
		if ($this->input->is_ajax_request())
		{
			$this->preview_gallery($entry->id, $field->id);
		}
		else
		{
			// Set notice
			Notice::add('Image deleted!');
			
			// Redirect
			admin_redirect('content/edit/'.$channel->slug_singular.'/'.$entry->id.'#'.$field->group_id);
		}
		
	} // remove_gallery_image()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Upload_admin


/* End of file upload_admin.php */