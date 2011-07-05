<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	/**
	 * Krustr form helper
	 *
	 * A couple of functions that extend the CI form helper
	 *
	 * @author 		Boris Strahija <boris@creolab.hr>
	 * @copyright 	Copyright (c) 2009, Boris Strahija
	 * @version 	0.1
	 * 
	 */
	 
	 
	 
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_header()
	{
		return fb_og_meta();
		
	} // end fb_header()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_footer()
	{
		$ci =& get_instance();
		$protocol = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
		
		$return = '<div id="fb-root"></div>'."\n".'
<script src="'.$protocol.'://connect.facebook.net/en_US/all.js"></script>'."\n".'
<script>'."\n".'
FB.init({appId: \''.fb_app_id().'\', status: true, cookie: true, xfbml: true});'."\n".'
FB.Event.subscribe("auth.sessionChange", function(response) {'."\n".'
	if (response.session) {'."\n".'
		// A user has logged in, and a new cookie has been saved'."\n".'
		window.location.reload();'."\n".'
	} else {'."\n".'
		// The user has logged out, and the cookie has been cleared'."\n".'
		window.location.reload();'."\n".'
	} // end if'."\n".'
});'."\n".'
</script>'."\n".'
		';
		
		/*if (fb_logged_in()) {
			$return .= "<script type='text/javascript'>$(document).ready(function(){ $('a.logout').click(function(){ var next = $(this).attr('href'); FB.logout(function(response){ window.location.href = next; return true; }); return false; });});</script>";
		} // end if
		*/
		return $return;
		
	} // end fb_footer()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_login_button($text = 'Login with Facebook', $size = 'medium')
	{
		return '<fb:login-button autologoutlink="true" size="'.$size.'" perms="email,user_birthday,read_stream,user_status,friends_status" background="white" length="short">'.$text.'</fb:login-button>';
		//return '<fb:login-button v="2" autologoutlink="true" size="'.$size.'" perms="email,user_birthday,read_stream,user_status,friends_status"><fb:intl>'.$text.'</fb:intl></fb:login-button>';
		
	} // end fb_login_button()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_logout_button($text = 'Logout')
	{
		return '<fb:login-button autologoutlink="true" size="medium" background="white" length="short"></fb:login-button>';
		
	} // end fb_logout_button()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_logout()
	{
		$ci =& get_instance();
		$ci->facebook->logout();
		
	} // end fb_logout()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_logged_in()
	{
		$ci =& get_instance();
		
		return $ci->facebook->getUser();
	
	} // end fb_logged_in()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_xmlns()
	{
		//return 'xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/"';
		return 'xmlns:og="http://opengraphprotocol.org/schema/"';
		
	} // end fb_xmlns()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_app_id()
	{
		$ci =& get_instance();
		
		return $ci->config->item('facebook_app_id');
		
	} // end fb_app_id()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_me()
	{
		$ci   =& get_instance();
		$user =  $ci->facebook->api('/me?fields=name,id,email,picture');
		
		if ($user)
		{
			return $user;
		}
		
		return '';
		
	} // end fb_me()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_name()
	{
		$ci   =& get_instance();
		$user =  $ci->facebook->api('/me?fields=name');
		
		if ($user and isset($user['name']))
		{
			return $user['name'];
		}
		
		return '';
		
	} // end fb_name()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_picture()
	{
		$ci   =& get_instance();
		$user =  $ci->facebook->api('/me?fields=picture');
		
		if ($user and isset($user['picture']))
		{
			return $user['picture'];
		}
		
		return null;
		
	} // end fb_picture()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_email()
	{
		$ci   =& get_instance();
		$user =  $ci->facebook->api('/me?fields=email');
		
		if ($user and isset($user['email']))
		{
			return $user['email'];
		}
		
		return null;
		
	} // end fb_email()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function fb_og_meta()
	{
		$ci =& get_instance();
		
		$return = '<meta property="fb:admins" content="'.$ci->config->item('facebook_admins').'">';
		$return .= "\n";
		$return .= '<meta property="fb:app_id" content="'.$ci->config->item('facebook_app_id').'">';
		$return .= "\n";
		$return .= '<meta property="og:site_name" content="'.$ci->config->item('facebook_site_name').'">';
		$return .= "\n";	 
		
		return $return;
		
	} // end ()
	
	
	/* ------------------------------------------------------------------------------------------ */


/* End of file facebook.php */