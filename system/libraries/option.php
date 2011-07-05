<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	

/**
 * Option Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Option {
	
	protected 	$ci;
	private 	$_data = array();
	
	/**
	 * The constructor
	 *
	 */
	function __construct()
	{
		// Get CI instance
		$this->ci =& get_instance();
		
		// Load resources
		$this->ci->load->model('setting_m');
		
		// Get all options
		$this->_cache_all();
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get($slug = null)
	{
		if (is_string($slug) && isset($this->_data[$slug])) :
			return $this->_data[$slug];
		
		elseif (is_array($slug)) :
			$data = array();
			foreach ($slug as $n) :
				if (is_string($n) && isset($this->_data[$n])) :
					$data[$n] = $this->_data[$n];
				endif;
			endforeach;
			return $data;
			
		else :
			return null;
		endif;
		
	} //end get()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function get_all()
	{
		return $this->_cache_all();
		
	} // end get_all()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function update($slug = null, $value = null)
	{
		if (isset($this->_data[$slug])) :
			$this->_data[$slug] = trim($value);
			$this->ci->setting_m->update_by(array('slug'=>$slug), array('value'=>trim($value)));
		
		else :
			$this->_data[$slug] = trim($value);
			$this->ci->setting_m->insert(array('slug'=>trim($slug), 'value'=>trim($value)));
		
		endif;
		
		// Remove from cache on update
		$this->ci->cache->delete('settings');
		
	} //end update()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _cache_all()
	{
		if ( ! $options = $this->ci->cache->get('settings')) {
			$options = $this->ci->setting_m->get_all();
			$this->ci->cache->save('settings', $options, 60*60*24); // Cache for a day
		} // end if
		
		// Add options to object
		foreach ($options as $option) :
			$this->_data[trim($option->slug)] = trim($option->value);
		endforeach;
		
		return $options;
		
	} //end _cache_all()

	
} //end Option


/* End of file option.php */