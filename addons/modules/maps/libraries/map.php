<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	

/**
 *  Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Map extends CMS {
	
	protected static $ci;
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		self::$ci =& get_instance();
		
		// Load resources
		$this->config->load('maps/maps');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function show($lat = null, $lng = null, $el_id = "google_map")
	{
		if ($lat and $lng)
		{
			$data = array(
				'el_id'   => $el_id,
				'api_key' => config_item('maps_api_key'),
				'lat'     => $lat,
				'lng'     => $lng,
			);
			
			self::$ci->load->view('maps/map', $data);
		}
		
	} // show()
	
	
	/* ------------------------------------------------------------------------------------------ *//* ------------------------------------------------------------------------------------------ */
	
} //end Map


/* End of file map.php */