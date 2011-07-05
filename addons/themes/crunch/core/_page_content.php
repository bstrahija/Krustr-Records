<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Setup entries for templates / pages
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Page_Content extends CMS {
	
	private $_setup = array();
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// The array is setup in this way:
		// 'current_page_slug_or_id'=>array('slug_or_id_of_entry_to_load', 'array_index_to_load_the_entry_into')
		
		// Setup all the special contents
		$this->_setup = array(
			'index'=>array(
				 array('slug'=>'about')
				,array(
					 'slug' => 'about'
					,'key'  => 'entry_about'
				)
				,array(
					 'key'     => 'recent_articles'
					,'channel' => 'articles'
					,'limit'   => 3
				)
			)
		);
		
		// Load 'em
		$this->_load();
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _load()
	{
		foreach ($this->_setup as $id=>$entries) {
			if (is_entry($id) or ($id == 'index' and is_home())) {
				foreach ($entries as $data) {
					// From channel
					if (isset($data['channel'])) {
						CMS::$front_data->content->{$data['key']} = entries($data['channel'], @$data['limit']);
					}
					
					// Single entry
					else {
						// Load entry for front view
						if ( ! isset($data['key'])) { // check if a key is set (this way you can acces the entry)
							CMS::$front_data->content->{$data['slug']} = entry($data['slug']);
						}
						else {
							CMS::$front_data->content->{$data['key']} = entry($data['slug']);
						} // end if
					} // end if
				} // end foreach
				
				return;
				
			} // end if
		} // end foreach
		
	} // end _load()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Page_Content


/* End of file _page_content.php */