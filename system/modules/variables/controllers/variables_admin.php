<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Variables Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Variables_admin extends Backend {
	
	// Container for all variables
	private $_vars = array();
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		
		// Restricted access
		Auth::restrict('editor');
		
		// Set navigation mark
		$this->set_nav_mark('content');
		$this->set_nav_mark('variables', 2);
		
		// Load resources
		$this->load->model('variables/variable_m');
		
		// Get all variables
		$this->_vars = $this->variable_m->get_all();
		Backend::set_data('vars', $this->_vars);
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		// Title and buttons
		$this->set_title('Variables');
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function add()
	{
		$this->set_title('Add New Variable');
		
		// Create the form
		$form = new Form();
		$form->open()
		     ->fieldset()
		     ->text(    'title', 'Name',  'required|trim|xss_clean', null)
		     ->textarea('value', 'Value', 'required|trim|xss_clean', null, 'class="variable-tiny"')
		     ->html('<p class="btns>')
		     	->submit('Save')
		     ->html('</p>')
		     ;
		
		// Form to view
		Backend::$data->form   = $form->get();
		Backend::$data->errors = $form->errors;
		
		// Validate
		if ($form->valid) {
			// Refresh cache
			$this->cache->delete('variables');
			
			// Save variable
			$id = $this->variable_m->insert(array(
				 'title' 		=> $this->input->post('title')
				,'value' 		=> $this->input->post('value')
			));
			Notice::add('Variable "'.$this->input->post('title').'" saved.');
			
			// Redirect
			admin_redirect('variables/edit/'.$id);
			
		} // end if
		
	} // end add()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function edit($id = NULL)
	{
		$this->set_title('Add New Variable');
		
		// Get variable
		$var = $this->variable_m->get($id);
		
		// Create the form
		$form = new Form();
		$form->open()
		     ->fieldset()
		     ->text(    'title', 'Name',  'required|trim|xss_clean', $var->title)
		     ->textarea('value', 'Value', 'required|trim|xss_clean', $var->value, 'class="variable-tiny rich"')
	     	->submit('Save')
		     ;
		
		// Form to view
		Backend::$data->form   = $form->get();
		Backend::$data->errors = $form->errors;
		
		// Validate
		if ($form->valid) {
			// Refresh cache
			$this->cache->delete('variables');
			
			// Save variable
			$this->variable_m->update($id, array(
				 'title' 		=> $this->input->post('title')
				,'value' 		=> $this->input->post('value')
			));
			Notice::add('Variable "'.$this->input->post('title').'" saved.');
			
			// Redirect
			admin_redirect('variables/edit/'.$id);
			
		} // end if
		
	} // end edit()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function delete($id = NULL)
	{
		$this->view = FALSE;
		
		if ($id) {
			// Refresh cache
			$this->cache->delete('variables');
			
			Notice::add('Variable deleted.');
			$this->variable_m->delete($id);
		} // end if
		
		// Redirect
		admin_redirect('variables');
		
	} // end delete()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _set_form_fields()
	{
		self::$_form_fields = array(
			 'fieldsets' => array(
				 'main'	=> array(
				 	 'title'	=> null
				 	,'fields'	=> array(
						 'title'	=> array(
							 'label'	=> 'Name'
							,'rules'	=> 'required|trim|xss_clean'
						)
						,'value'	=> array(
							 'label'	=> 'Value'
							,'type'		=> 'textarea'
							,'class'	=> 'variable-tiny'
							,'rules'	=> 'required|trim|xss_clean'
						)
						,'submit'	=> array(
							 'label'	=> 'Save'
							,'type'		=> 'submit'
						)
				 	) // end fields
				) // end main fieldset
			) // end fieldsets
		); // end form_fields);
		
	} // end _set_form_fields()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Variables_admin


/* End of file variables_admin.php */