<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	

/**
 * Video Embed Library
 *
 * Planned support for following video sites:
 * YouTube, Google, Blip.TV, Revver, MySpace, MetaCafe, Last.fm, JumpCut, 
 * SevenLoad, Spike TV, Daily Motion, Veoh, Vimeo, Tudou, imeem, Guba, 
 * Yahoo Music Video
 * 
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2009, Boris Strahija, Creo
 * @version 	0.2
 */

class Video_embed {
	
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
	public function show($url = null, $width = 320, $height = 240, $type = 'iframe')
	{
		/* YouTube */
		if ($this->_get_video_source($url) == 'youtube')
		{
			$video_id = $this->get_video_id($url);
			$url = "http://www.youtube.com/v/".$video_id;
			
			if ($type == 'iframe')
			{
				return '<iframe title="YouTube video player" width="'.$width.'" height="'.$height.'" src="'.$url.'?rel=0" frameborder="0" allowfullscreen></iframe>';
			}
			else
			{
				return '<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.youtube-nocookie.com/v/'.$video_id.'?version=3&amp;hl=en_US&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/'.$video_id.'?version=3&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
			}
			//return '<object width="'.$width.'" height="'.$height.'"><param name="wmode" value="transparent"> <param name="movie" value="'.$url.'"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="'.$url.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="transparent" width="'.$width.'" height="'.$height.'"></embed></object>'; 
		}
		
		/* Vimeo */
		elseif ($this->_get_video_source($url) == 'vimeo')
		{
			$video_id = $this->get_video_id($url);
		    $url = "http://vimeo.com/moogaloop.swf?clip_id=".$video_id."&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=19a3ff&amp;fullscreen=1";
			return '<object width="'.$width.'" height="'.$height.'"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="'.$url.'" /><embed src="'.$url.'" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="'.$width.'" height="'.$height.'"></embed></object>';
			
		}
		
	} //end show()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function url($url = null)
	{
		/* YouTube */
		if ($this->_get_video_source($url) == 'youtube') :
			$url = 'http://www.youtube.com/v/'.$this->get_video_id($url);
		endif;
		
		return $url;
		
	} //end url()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function _get_video_source($url = null)
	{
		if (strpos($url, 'youtube.com/') !== false) :
			return 'youtube';
		
		elseif (strpos($url, 'vimeo.com/') !== false) :
			return 'vimeo';
			
		endif;
		
		return 'invalid';
		
	} //end ()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_video_thumb($url = null)
	{
		$video_id = $this->get_video_id($url);

		/* YouTube */
		if ($this->_get_video_source($url) == 'youtube') :
			return 'http://img.youtube.com/vi/'.$video_id.'/2.jpg';
		
		/* Vimeo */
		elseif ($this->_get_video_source($url) == 'vimeo') :
			return $this->get_vimeo_info($video_id, 'thumbnail_medium');
			
		endif;			
		
	} //end get_video_thumb()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_video_id($url = null)
	{
		/* YouTube */
		if ($this->_get_video_source($url) == 'youtube') :
			// Change http://www.youtube.com/watch?v=28G9aAnA2RI to http://www.youtube.com/v/28G9aAnA2RI
			preg_match("/v=([^(\&|$)]*)/", $url, $matches);
			if (@$matches[1]) :
				$videocode = @$matches[1];
			else :
				$matches = explode('/', $url);
				$videocode = $matches[count($matches)-1];
			endif;
			return $videocode;
		
		/* Vimeo */
		elseif ($this->_get_video_source($url) == 'vimeo') :
		    $url = str_replace('http://www.vimeo.com/','',$url);
		    $url = str_replace('http://vimeo.com/','',$url);
		    $videocode = $url;
		    return $videocode;
			
		endif;
	
	} //end get_video_id()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function get_vimeo_info($id, $info = 'thumbnail_medium') {
		if (!function_exists('curl_init')) die('CURL is not installed!');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/$id.php");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = unserialize(curl_exec($ch));
		$output = $output[0][$info];
		curl_close($ch);
		return $output;
	} // get_vimeo_info()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Video_embed


/* End of file video_embed.php */