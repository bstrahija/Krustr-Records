<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Theme Helper
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Theme extends CMS {
	
	protected static $ci;
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function partial($file = null, $return = false)
	{
		self::$ci    = get_instance();
		$base        = realpath(BASEPATH.'/../../');
		$data        = CMS::$front_data;
		$theme_path  = $data->theme_path;
		$return_data = null;
		
		// Check root
		$view = reduce_double_slashes($theme_path.'/'.$file);
		
		if (file_exists($base.'/'.$view.'.php'))
		{
			$return_data = self::$ci->parser->parse('../../'.$view, $data, true);
		}
		
		
		// Check core
		$view = reduce_double_slashes($theme_path.'/partial_core/'.$file);
		
		if (file_exists($base.'/'.$view.'.php'))
		{
			$return_data = self::$ci->parser->parse('../../'.$view, $data, true);
		}
		
		
		// Check partial
		$view = reduce_double_slashes($theme_path.'/partial/'.$file);
		
		if (file_exists($base.'/'.$view.'.php'))
		{
			$return_data = self::$ci->parser->parse('../../'.$view, $data, true);
		}
		
		// Echo / return
		if ($return) return $return_data;
		else         echo   $return_data;
		
	} // partial()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end 




/* !✰ Functions */


/**
 *
 */
function partial($file = null)
{
	Theme::partial($file);
	
} // partial()

/* ------------------------------------------------------------------------------------------ */



/* End of file theme_helper.php */