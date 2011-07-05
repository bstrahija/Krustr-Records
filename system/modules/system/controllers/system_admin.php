<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	

/**
 * System Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2009, Boris Strahija, Creo
 * @version 	0.2
 */

class System_admin extends Backend {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Call the MY_Controller constructor
		parent::__construct();
		
		// Restricted access
		Auth::restrict('admin');
		
		// Set navigation mark
		$this->set_nav_mark('system');
		$this->set_nav_mark('users', 2);
		
		// Load resources
		$this->load->model('users/user_m');
		
	} // end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		admin_redirect('system/maintenance');
		
	} // end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function maintenance()
	{
		// Mark the navi
		$this->set_nav_mark('maintenance', 2);
		
		// Set the title
		Backend::set_title('Site maintenance');
		Backend::set_data('subtitle', 'You can set the status of your website here');
		
		// Build form
		$form = new Form();
		$form->open(current_url())
		     ->fieldset()
	     	->select('site_status', 
	     	             array(
			                 'online'  => 'Online',
	                         'offline' => 'Offline'
			             ), 
			             'Site status',
			             $this->option->get('site_status')
			         )
		    ->html('<p class="field-info">When set to "Online", all visitors will be able to browse your site normally. When set to "Off-line", only users with the "administer site configuration" permission will be able to access your site to perform maintenance; all other visitors will see the site off-line message configured below. Authorized users can log in during "Off-line" mode directly via the user login page.</p>')
		    ->textarea('site_offline_message', 'Site offline message', 'required|trim|xss_clean', $this->option->get('site_offline_message'))
		    ->html('<p class="btns">')
		    	->submit('Save')
		    ->html('</p>')
		    ;
		
		// Form to view
		Backend::$data->form   = $form->get();
		Backend::$data->errors = $form->errors;
		
		if ($form->valid) :
			$status = $this->input->post('site_status');
			$this->setting_m->update_by(array('slug'=>'site_status'), 			array('value'=>$status[0]));
			$this->setting_m->update_by(array('slug'=>'site_offline_message'), 	array('value'=>$this->input->post('site_offline_message')));
			Notice::add('Settings saved.');
			
			// Redirect
			admin_redirect('system/maintenance');
			
		endif;
		
	} // end maintenance()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function navigation()
	{
		
	} // end navigation()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function php()
	{
		
	} // php()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} // end System_admin


/* End of file system_admin.php */