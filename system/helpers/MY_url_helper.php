<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ------------------------------------------------------------------------------------------ */

/**
 * Return a entry URL, useful for multilang
 *
 */
function url($uri = null) {
	$CI =& get_instance();
	
	if ($CI->config->item('multilang'))
	{
		return make_http(site_url(SITE_LANG.'/'.$uri));
	}
	else
	{
		return make_http(site_url($uri));
	}
	
} // url()


/* ------------------------------------------------------------------------------------------ */

/**
 * Returns HTTPS URL
 *
 */
function url_https($uri = null)
{
	return make_https(url($uri));
	
} // url_https()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function url_auto_protocol($url = null)
{
	return str_replace(array('http://', 'https://'), array('//', '//'), $url);
	
} // url_auto_protocol()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function redirect_https($uri = null)
{
	redirect(make_https($uri));
	
} // redirect_https()


/* ------------------------------------------------------------------------------------------ */

/**
 * Convert a http url into a https
 *
 */
function make_https($url)
{
 	// Get CI instance
	$ci =& get_instance();
	
	if ($url and ENVIRONMENT === 'production') :
		$url = prep_url($url);
		$url = str_replace('http://', 'https://', $url);
	endif;
	
	return $url;
	
} //end make_https()


/* ------------------------------------------------------------------------------------------ */

/**
 * Convert a https url into a http
 *
 */
function make_http($url = null)
{
	if ($url) :
		$url = prep_url($url);
		$url = str_replace('https://', 'http://', $url);
		return $url;
	endif;
	
	return NULL;
	
} //end make_http()