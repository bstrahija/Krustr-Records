<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6" <?php echo fb_xmlns(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7" <?php echo fb_xmlns(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8" <?php echo fb_xmlns(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9" <?php echo fb_xmlns(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js" <?php echo fb_xmlns(); ?>> <!--<![endif]-->
*/



/* ------------------------------------------------------------------------------------------ */

/**
 * Returns short string for browser id
 *
 */
function browser_class()
{
	// Get CI instance
	$ci =& get_instance();
	$ci->load->library('user_agent');
	
	// Deafult browser class
	$class = '';
	
	// Get browser
	if ($ci->agent->browser() === 'Internet Explorer') {
		$class = 'ie';
		if ($ci->agent->version() < 7) 		$class .= ' ie6';
		elseif ($ci->agent->version() < 8) 	$class .= ' ie7';
		elseif ($ci->agent->version() < 9) 	$class .= ' ie8';
		else 								$class .= ' ie9';
	}
	
	elseif ($ci->agent->browser() === 'Firefox') {
		$class = 'firefox';
		if ($ci->agent->version() < 3) 			$class .= ' firefox2';
		elseif ($ci->agent->version() < 3.6) 	$class .= ' firefox3';
		elseif ($ci->agent->version() < 4) 		$class .= ' firefox36';
		else 									$class .= ' firefox4';
	}
		
	elseif ($ci->agent->browser() === 'Opera') {
		$class = 'opera';
	}
		
	elseif ($ci->agent->browser() === 'Safari') {
		$class = 'safari';
		
	} // end if
	
	// Return it
	return $class;
		
} // end browser_class()
