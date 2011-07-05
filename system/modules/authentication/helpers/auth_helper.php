<?php

/**
 * Authentication helper
 *
 * A couple of functions used by the Authentication library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija
 * @version 	0.9
 * 
 */



/* ------------------------------------------------------------------------------------------ */

/**
 * Are you loged in
 *
 */
function logged_in()
{
	return Auth::logged_in();
		
} // end logged_in()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function is_admin($user_id = null)
{
	return Auth::is_admin($user_id);
		
} // end is_admin()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function is_superadmin($user_id = null)
{
	return Auth::is_superadmin($user_id);
	
} // end is_superadmin()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function get_user($id = null)
{
	return Auth::get_user($id);
	
} // end get_user()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function username($id = null)
{
	return Auth::get_user_var('username', $id);
	
} // end username()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function user_email($id = null)
{
	return Auth::get_user_var('email', $id);
	
} // end user_email()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function user_first_name($id = null)
{
	return Auth::get_user_var('first_name', $id);
	
} // end user_first_name()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function user_last_name($id = null)
{
	return Auth::get_user_var('last_name', $id);
	
} // end user_last_name()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function user_display_name($id = null)
{
	return Auth::get_user_var('display_name', $id);
	
} // end user_display_name()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function user_id()
{
	return Auth::get_user_var('id');
	
} // end user_id()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function user_var($var = null, $id = null)
{
	return Auth::get_user_var($var, $id);
	
} // end user_var()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function user_avatar($id = null, $width = 32, $height = 32)
{
	return Auth::get_user_avatar($id, $width, $height);
	
} // end user_avatar()


/* ------------------------------------------------------------------------------------------ */

/* End of file auth_helper.php */