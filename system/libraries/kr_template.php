<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * CMS template parsing library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class KR_Template extends Front {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function __construct()
	{
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function render()
	{
		// Get the view that is supposed to be loaded
		$this->benchmark->mark('KR_Find_Request_View_start');
		$view = $this->request->view();
		$this->benchmark->mark('KR_Find_Request_View_end');
		
		// Moved because of order of loading
		$this->benchmark->mark('KR_Load_Actions_Library_start');
		$this->load->library('kr_actions', 	null, $this->actions_instance);
		$this->benchmark->mark('KR_Load_Actions_Library_end');
		
		// Get parsed content
		$this->benchmark->mark('KR_Load_Content_start');
		$content = $this->_load_view($view);
		$this->benchmark->mark('KR_Load_Content_end');
		
		// Output
		$this->benchmark->mark('KR_Output_Content_start');
		$this->output->set_output($content);
		$this->benchmark->mark('KR_Output_Content_end');
		
	} //end render()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _load_view($view = null)
	{
		if ($view) {
			// Check if parsing of tags is enabled
			if (CMS::$enable_parser) {
				return $this->parser->parse($view, CMS::$front_data, true);
			}
			
			else {
				return $this->load->view($view, CMS::$front_data, true);
			} // end if
			
		} // end if
		
		return null;
		
	} // end _load_view()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end KR_Template


/* End of file kr_template.php */