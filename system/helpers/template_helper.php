<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class KR_Template_Helper {
	
	protected static $ci;
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		self::$ci = get_instance();
		
	} // end __construct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function test_func($val = null, $param2 = null, $param3 = null)
	{
		return 'Test: '.$val;
		
	} // end test_func()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function thumb($val = null, $attrs = null)
	{
		// Defaults
		$width      = (isset($attrs['width']))      ?    (int) $attrs['width']      : 100;
		$height     = (isset($attrs['height']))     ?    (int) $attrs['height']     : 100;
		$crop       = (isset($attrs['crop']))       ?   (bool) $attrs['crop']       : false;
		$master_dim = (isset($attrs['master_dim'])) ? (string) $attrs['master_dim'] : 'auto';
		$overwrite  = (isset($attrs['overwrite']))  ?   (bool) $attrs['overwrite']  : false;
		
		return image_thumb($val, $width, $height, $crop, $master_dim, $overwrite);
		
	} // end thumb()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function gallery($val = null, $attrs = null)
	{
		if ($val)
		{
			return gallery($val);
		}
		
		return null;
		
	} // end gallery()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function summary($val = null, $attrs = null)
	{
		return character_limiter(strip_tags($val), (int) @$data['length']);
		
	} // end summary()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function date($val = null, $attrs = null)
	{
		return date($attrs['format'], $val);
		
	} // end date()
	
	/* ------------------------------------------------------------------------------------------ */
	
}

