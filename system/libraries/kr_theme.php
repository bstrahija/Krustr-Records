<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Krustr Theme Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.30
 */

class KR_Theme extends CMS {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Start benchmark
		$this->benchmark->mark('KR_Theme_Constructor_start');
		
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function partial($partial_view = null, $data = null)
	{
		if ($partial_view) {
			if ($data) {
				if (CMS::$enable_parser) echo $this->parser->parse('../../'.CMS::$current_theme_path.'/'.$partial_view, $data, true);
				else                     $this->load->view('../../'.CMS::$current_theme_path.'/'.$partial_view, $data);
				
			}
			
			else {
				if (CMS::$enable_parser) echo $this->parser->parse('../../'.CMS::$current_theme_path.'/'.$partial_view, CMS::$front_data, true);
				else                     $this->load->view('../../'.CMS::$current_theme_path.'/'.$partial_view, CMS::$front_data);
				
			} // end if
			
		} // end if
		
		return null;
		
	} //end partial()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function partial_require($file = null)
	{
		if ($file) {
			require(BASEPATH.'../../addons/themes/bigdeal/'.$file.'.php');
		} // end if
		
		return null;
		
	} // end partial_require()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function comments()
	{
		// Get comments
		if (self::$data['entry']) :
			self::$data['entry']->comments = $this->comment->order_by('created_at', 'DESC')->get_many_by(array(
				 'entry_id'=>self::$data['entry']->id
				,'status'=>'published'
			));
		endif;
		
		$this->load->view('../../'.$this->theme_path.'/inc/comments');
		
	} //end comments()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function comment_form()
	{
		$this->load->view('../../'.$this->theme_path.'/inc/comment_form');
		
	} //end comment_form()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function home()
	{
		return site_url();
		
	} //end home()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function path($url = null)
	{
		return site_url(self::$current_theme_path.'/'.$url);
		
	} //end path()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function feed()
	{
		
		return null;
		
	} // end feed()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	
} //end KR_Theme


/* End of file kr_theme.php */