<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS helper
 *
 * A couple of functions that are used by the Krustr CMS for the themes
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija
 * @version 	0.1
 * 
 */



/* ------------------------------------------------------------------------------------------ */
/* !/==> Core partials */
/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function get_header($return = false)
{
	get_partial('core/header', $return);
	
} // end get_header()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function get_footer($return = false)
{
	get_partial('core/footer', $return);
	
} // end get_footer()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function get_aside($return = false)
{
	get_partial('core/aside', $return);
	
} // end get_aside()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function meta_tags($return = false)
{
	get_partial('core/meta_tags', $return);
	
} // end get_meta_tags()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function get_partial($partial = null, $return = false)
{
	$ci =& get_instance();
	
	$ci->theme->partial($partial);
	
} // end get_partial()

/* ------------------------------------------------------------------------------------------ */



/* ------------------------------------------------------------------------------------------ */
/* !/==> Content helpers */
/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function title($entry = null, $return = false)
{
	// Get entry
	if ( ! $entry) {
		$entry = CMS::$front_data->content->entry;
	} // end if
	
	// Data
	if ($entry) {
		if ($return) return $entry->title;
		else         echo   $entry->title;
	} // end if
	
	return '';
	
} // end title()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function body($entry = null, $format = true, $return = false)
{
	// Get entry
	if ( ! $entry) {
		$entry = CMS::$front_data->content->entry;
	} // end if
	
	// Data
	if ($entry) {
		$content = $entry->body;
		
		if ($format) $content = auto_typography($content);
		
		if ($return) return $content;
		else         echo   $content;
	} // end if
	
	return '';
	
} // end body()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function summary($entry = null, $limit = null, $from_body = false, $return = false)
{
	// Get entry
	if ( ! $entry) {
		$entry = CMS::$front_data->content->entry;
	} // end if
	
	if ($entry) {
		// Get summary
		$plain_summary = trim(strip_tags($entry->summary));
		
		// If no summary is set get it from the body
		if ( ! $plain_summary or $from_body) {
			// Char limit
			if ( ! $limit) $limit = 300;
			
			$plain_summary = character_limiter(trim(strip_tags($entry->body)), $limit);
		} // end if
		
		if ($return) return @$plain_summary;
		else         echo   @$plain_summary;
		
	} // end if
	
	return '';
	
} // end summary()


/* ------------------------------------------------------------------------------------------ */

/**
 * Returns a permalink to the entry
 * If a label is passed a whole <a> tag is generated
 *
 */
function permalink($entry = null, $label = null)
{
	if ($entry) {
		if ($entry->channel == 'pages') $link = $entry->slug;
		else                           	$link = $entry->trigger_url.'/'.$entry->slug;
		
		if ($label) return anchor($link, $label);
		else        return site_url($link);
	} // end if
	
	return site_url();
	
} // end permalink()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function entry($id = null, $get_fields = true)
{
	$ci =& get_instance();
	
	if (is_numeric($id)) $entry = $ci->entry_m->get_extended($id);
	else                 $entry = $ci->entry_m->get_extended(array('slug'=>$id));
	
	// Custom fields
	if ($entry and $get_fields) {
		$entry->fields = fields($entry);
	} // end if
	
	return $entry;
	
} // end entry()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function load_entry($id = null, $get_fields = true)
{
	$entry = entry($id, $get_fields);
	if ($entry) CMS::$front_data->{'entry_'.$entry->slug} = $entry;
	
} // end load_entry()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function entries($channel = 'articles', $limit = 5, $get_fields = false)
{
	$ci =& get_instance();
	
	$entries = $ci->entry_m->limit($limit)->get_many_extended(array(
		'channel' => $channel
	));
	
	return $entries;
	
} // end entries()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function fields($entry = null, $entry_id = null)
{
	$ci =& get_instance();
	
	return $ci->entry_m->fields($entry, $entry_id);
	
} // end fields()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function cleanup_html($html = '')
{
	$htmlp_config = HTMLPurifier_Config::createDefault();
	$htmlp_config->set('AutoFormat.RemoveEmpty', true);
	$htmlp_config->set('AutoFormat.AutoParagraph', true);
	$purifier = new HTMLPurifier($htmlp_config);
	$html     = $purifier->purify( $html );
	
	$html = str_replace(
	                    array('<p></p>', '<p><br></p>', '<p><br /></p>', '<p class="p1"><br></p>', '<p class="p1"><br /></p>', '<div><br /></div>', '<div><br></div>'), 
	                    array('',        '',            '',              '',                       '',                         '',                  ''), 
	                    $html
	                    );
		
	return $html;
	
} // end cleanup_html()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function cleanup_word_html($text, $allowed_tags = '<b><i><sup><sub><em><strong><u><br>') 
{ 
	mb_regex_encoding('UTF-8'); 
	//replace MS special characters first 
	$search = array('/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u'); 
	$replace = array('\'', '\'', '"', '"', '-'); 
	$text = preg_replace($search, $replace, $text); 
	//make sure _all_ html entities are converted to the plain ascii equivalents - it appears 
	//in some MS headers, some html entities are encoded and some aren't 
	$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8'); 
	//try to strip out any C style comments first, since these, embedded in html comments, seem to 
	//prevent strip_tags from removing html comments (MS Word introduced combination) 
	if(mb_stripos($text, '/*') !== FALSE){ 
		$text = mb_eregi_replace('#/\*.*?\*/#s', '', $text, 'm'); 
	} 
	//introduce a space into any arithmetic expressions that could be caught by strip_tags so that they won't be 
	//'<1' becomes '< 1'(note: somewhat application specific) 
	$text = preg_replace(array('/<([0-9]+)/'), array('< $1'), $text); 
	$text = strip_tags($text, $allowed_tags); 
	//eliminate extraneous whitespace from start and end of line, or anywhere there are two or more spaces, convert it to one 
	$text = preg_replace(array('/^\s\s+/', '/\s\s+$/', '/\s\s+/u'), array('', '', ' '), $text); 
	//strip out inline css and simplify style tags 
	$search = array('#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu', '#<(em|i)[^>]*>(.*?)</(em|i)>#isu', '#<u[^>]*>(.*?)</u>#isu'); 
	$replace = array('<b>$2</b>', '<i>$2</i>', '<u>$1</u>'); 
	$text = preg_replace($search, $replace, $text); 
	//on some of the ?newer MS Word exports, where you get conditionals of the form 'if gte mso 9', etc., it appears 
	//that whatever is in one of the html comments prevents strip_tags from eradicating the html comment that contains 
	//some MS Style Definitions - this last bit gets rid of any leftover comments */ 
	$num_matches = preg_match_all("/\<!--/u", $text, $matches); 
	if($num_matches){ 
		  $text = preg_replace('/\<!--(.)*--\>/isu', '', $text); 
	} 
	return $text; 
}


function remove_empty_tags($html_replace)
{
	$pattern = '/<p[^>]*(?:\/>|>(?:\s|&nbsp;)*<\/p>)/im';
	return preg_replace($pattern, '', $html_replace);
}


function strip_html_tags( $text )
{
  // PHP's strip_tags() function will remove tags, but it
  // doesn't remove scripts, styles, and other unwanted
  // invisible text between tags.  Also, as a prelude to
  // tokenizing the text, we need to insure that when
  // block-level tags (such as <p> or <div>) are removed,
  // neighboring words aren't joined.
  $text = preg_replace(
    array(
      // Remove invisible content
      '@<head[^>]*?>.*?</head>@siu',
      '@<style[^>]*?>.*?</style>@siu',
      '@<script[^>]*?.*?</script>@siu',
      '@<object[^>]*?.*?</object>@siu',
      '@<embed[^>]*?.*?</embed>@siu',
      '@<applet[^>]*?.*?</applet>@siu',
      '@<noframes[^>]*?.*?</noframes>@siu',
      '@<noscript[^>]*?.*?</noscript>@siu',
      '@<noembed[^>]*?.*?</noembed>@siu',

      // Add line breaks before & after blocks
      /*'@<((br)|(hr))@iu',
      '@</?((address)|(blockquote)|(center)|(del))@iu',
      '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
      '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
      '@</?((table)|(th)|(td)|(caption))@iu',
      '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
      '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
      '@</?((frameset)|(frame)|(iframe))@iu',*/
    ),
    array(
      ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
      ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
    ),
    $text );

  // Remove all remaining tags and comments and return.
  return $text;
}



/* ------------------------------------------------------------------------------------------ */
/* !/==> Path helpers */
/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function theme_url($url = null)
{
	return reduce_double_slashes(site_url(CMS::$current_theme_path.'/'.$url).'/');
	
} // end theme_url()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function uri_segment($num = 1)
{
	$segment = @CMS::$front_data->uri_segment[$num];
	
	return $segment;
	
} // end uri_segment()



/* ------------------------------------------------------------------------------------------ */
/* !/==> Images */
/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function image_thumb($file_path = NULL, $width = 100, $height = 100, $crop = FALSE, $master_dim = 'auto', $overwrite = FALSE)
{
	// Get CI instance
	$ci =& get_instance();
	
	if (is_file($file_path)) :
		// Calculate aspect ratios
		$image_size = getimagesize($file_path);
		
		// Get folder path and file name
		$folder 	= explode("/", $file_path);
		$file_name 	= array_pop($folder);
		$folder 	= implode("/", $folder);
		
		$thumb_folder 	= $folder.'/'.$width.'x'.$height;
		if ($crop) 	$thumb_folder = $thumb_folder.'_cropped/';
		else 		$thumb_folder = $thumb_folder.'/';
		$new_file_path 	= $thumb_folder.$file_name;
		
		// Create folder for thumbs
		if ( ! is_dir($thumb_folder)) :
			@mkdir($thumb_folder, 0777);
			@chmod($thumb_folder, 0777);
		endif;
		
		//echo $file_path, '<br />';
		//echo $new_file_path, '<br /><br />';
		
		// Resize only if image doesn't already exists
		if ( ! is_file($new_file_path) || $overwrite) :
			// If dimmensions are square
			if ($width == $height) :
				$ci->image_resize->squareThumb(FCPATH.$file_path, FCPATH.$new_file_path, $width);
			else :
				$ci->image_resize->resize(FCPATH.$file_path, $width, $height, FCPATH.$new_file_path, true);
			endif;
		endif;
		
		return site_url($new_file_path);
	endif;
	
} //end image_thumb()




function image($image_path, $width = 0, $height = 0, $crop = true) {
    //Get the Codeigniter object by reference
    $CI = & get_instance();
     
    // Get folder path and file name
	$folder 	= explode("/", $image_path);
	$file_name 	= array_pop($folder);
	$folder 	= implode("/", $folder);
	
	$thumb_folder 	= $folder.'/'.$width.'x'.$height;
	$thumb_folder 	= $thumb_folder.'/';
	$new_image_path = $thumb_folder.$file_name;
	
	// Create folder for thumbs
	if ( ! is_dir($thumb_folder)) :
		@mkdir($thumb_folder, 0777);
		@chmod($thumb_folder, 0777);
	endif;
    
    
     
    //The first time the image is requested
    //Or the original image is newer than our cache image
    if ((! file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path)) {
        $CI->load->library('image_lib');
        
        //The original sizes
        $original_size = @getimagesize($image_path);
        $original_width = $original_size[0];
        $original_height = $original_size[1];
        
        if ($original_width and $original_height) {
			$ratio = $original_width / $original_height;
	        
	        //The requested sizes
	        $requested_width = $width;
	        $requested_height = $height;
	        
	        //Initialising
	        $new_width = 0;
	        $new_height = 0;
	        
	        //Calculations
	        if ($requested_width > $requested_height) {
	            $new_width = $requested_width;
	            $new_height = $new_width / $ratio;
	            if ($requested_height == 0)
	                $requested_height = $new_height;
	            
	            if ($new_height < $requested_height) {
	                $new_height = $requested_height;
	                $new_width = $new_height * $ratio;
	            }
	        
	        }
	        else {
	            $new_height = $requested_height;
	            $new_width = $new_height * $ratio;
	            if ($requested_width == 0)
	                $requested_width = $new_width;
	            
	            if ($new_width < $requested_width) {
	                $new_width = $requested_width;
	                $new_height = $new_width / $ratio;
	            }
	        }
	        
	        $new_width = ceil($new_width);
	        $new_height = ceil($new_height);
	        
	        //Resizing
	        $config = array();
	        $config['image_library'] = 'gd2';
	        $config['source_image'] = $image_path;
	        $config['new_image'] = $new_image_path;
	        $config['maintain_ratio'] = FALSE;
	        $config['height'] = $new_height;
	        $config['width'] = $new_width;
	        $CI->image_lib->initialize($config);
	        $CI->image_lib->resize();
	        $CI->image_lib->clear();
	        
	        //Crop if both width and height are not zero
	        if (($width != 0) and ($height != 0)) {
	            $x_axis = floor(($new_width - $width) / 2);
	            $y_axis = floor(($new_height - $height) / 2);
	            
	            //Cropping
	            $config = array();
	            $config['source_image'] = $new_image_path;
	            $config['maintain_ratio'] = FALSE;
	            $config['new_image'] = $new_image_path;
	            $config['width'] = $width;
	            $config['height'] = $height;
	            $config['x_axis'] = $x_axis;
	            $config['y_axis'] = $y_axis;
	            $CI->image_lib->initialize($config);
	            $CI->image_lib->crop();
	            $CI->image_lib->clear();
	        } // end if
	    } // end if
    } // end if
     
    return site_url($new_image_path);
}


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function gallery($gallery_id = null, $width = 64, $height = 64, $return = 'html')
{
	if ($gallery_id)
	{
		$html = '';
		
		//Get the Codeigniter object by reference
		$ci = & get_instance();
		
		// Get gallery and images
		$gallery = $ci->gallery_m->get($gallery_id);
		$images  = $gallery->images = $ci->gallery_image_m->get_many_by("gallery_id", $gallery_id);
		
		// Add images to html
		if ($gallery and $images)
		{
			$html .= '<ul class="gallery-list">';
			
			foreach ($images as $image)
			{
				$html .= '<li><a href="'.site_url($image->file_path).'"><img src="'.image_thumb($image->file_path, $width, $height, true, 'auto').'" width="'.$width.'" height="'.$height.'" alt=""></a></li>';
			}
			
			$html .= '</ul>';
		}
		
		if ($return == 'html') return $html;
		else                   return $gallery;
	}
	
	return null;
	
} // end gallery()



/* ------------------------------------------------------------------------------------------ */
/* !/==> Misc */
/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function site_name()
{
	return CMS::$front_data->site_name;
	
} // end site_name()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function site_slogan()
{
	return CMS::$front_data->site_slogan;
	
} // end site_slogan()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function site_description()
{
	return CMS::$front_data->meta_description;
	
} // end site_description()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function set_var($var = null, $val = null)
{
	if ($var) {
		CMS::$front_data->{$var} = $val;
	} // end if
	
	return $val;
	
} // end set_var()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function get_var($var = null)
{
	if ($var and isset(CMS::$front_data->{$var})) {
		return CMS::$front_data->{$var};
	} // end if
	
	return null;
	
} // end get_var()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function is_entry($identifier = NULL)
{
	// Get CI instance
	$ci =& get_instance();
	
	// Get entry
	if (isset(CMS::$front_data->content))
	{
		$entry = @CMS::$front_data->content->entry;
		
		if ($entry and is_string($identifier))
		{
			if ($entry->slug == $identifier) return true;
		
		}
		elseif ($entry and is_numeric($identifier))
		{
			if ($entry->id == $identifier) return true;
		
		}
	}
	
	return false;
	
} // end is_entry()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function is_home()
{
	$ci =& get_instance();
	
	if ( ! isset(CMS::$uri_segment[1])) return true;
	
	return false;
	
} //end is_home()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function is_channel($channel = null)
{
	if ( ! $channel and CMS::$front_data->is_channel) return true;
	
	if ($channel and CMS::$front_data->channel) {
		if ($channel == CMS::$front_data->channel->slug or
		    $channel == CMS::$front_data->channel->slug_singular or
		    $channel == CMS::$front_data->channel->url_trigger) {
		
			return true;
		} // end if
	} // end if
	
	return false;
	
} // end is_channel()


/* ------------------------------------------------------------------------------------------ */




/* End of file cms_helper.php */