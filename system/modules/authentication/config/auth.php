<?php

/**
 * Authentication configuration
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija
 * @version 	0.9
 * 
 */


/*
 * Login and registration options
 *
 */
$config['auth']['identity']         = 'both'; // 'username', 'email' or 'both'
$config['auth']['remember_enable']  = true;   // Remeber a user, if 'remember_always' is off, a checkbox is required
$config['auth']['remember_always']  = true;   // Always remember the user
$config['auth']['email_activation'] = true;   // Activation is required when registering new user


/**
 * Type of hash algorithm to use
 *
 * Some examples are sha1, md5 (don't use this), sha256, etc.
 *
 */
$config['auth']['hash_algo'] = 'sha256';


/**
 * Salt Key.
 *
 * Generate a salt key from the following website: 
 * https://www.grc.com/passwords.htm
 *
 */
$config['auth']['salt']				= 's135hSgEeGMFcs1LURHkuSJAimaSopMCOCvjkYRHRCCrrwalgrvlyduBVT4juWg';


/*
 * Database tables
 *
 */
$config['auth']['users_table'] 		= 'users';
$config['auth']['user_meta_table'] 	= 'user_meta';


/*
 * Meta data columns
 *
 */
$config['auth']['meta_columns'] 		= array(
	 'first_name' 	=> 'varchar(150) default NULL'
	,'last_name' 	=> 'varchar(150) default NULL'
	,'display_name' => 'varchar(200) default NULL'
	,'avatar' 		=> 'varchar(200) default NULL'
	,'bio' 			=> 'varchar(200) default NULL'
	,'address' 		=> 'varchar(200) default NULL'
	,'postal_code' 	=> 'varchar(200) default NULL'
	,'city' 		=> 'varchar(200) default NULL'
	,'country' 		=> 'varchar(200) default NULL'
	,'phone' 		=> 'varchar(200) default NULL'
);


/*
 * User groups and levels
 *
 */
$config['auth']['user_groups'] 		= array(
	 'uberadmin' 	=> 9999
	,'superadmin' 	=> 999
	,'admin' 		=> 100
	,'editor' 		=> 50
	,'author' 		=> 30
	,'member' 		=> 1
);
$config['auth']['default_level'] 		= 1;


/*
 * Pages
 *
 */
$config['auth']['login_page'] 	= BACKEND.'/authentication/login';
$config['auth']['logout_page'] = BACKEND.'/authentication/logout';


/*
 * Administrator email address (all messages are sent from this address)
 *
 */
$config['auth']['admin_name'] 			= 'Mudra kupovina';
$config['auth']['admin_email'] 		= 'info@mudrakupovina.hr';


/*
 * Cookie expiration
 *
 */
$config['auth']['cookie_name'] 		= 'user_remember_key'; 	// Name of the cookie for the remember me function
$config['auth']['cookie_expires'] 		= '1209600'; 			// Time until the cookie expires (2 weeks)


/*
 * Email sending options and templates
 *
 */
$config['auth']['email_type'] 						= 'html';
$config['auth']['email_protocol'] 					= 'mail';
$config['auth']['email_tpl_path'] 					= 'authentication/_email';
$config['auth']['tpl_activate'] 					= 'activate';
$config['auth']['tpl_forgot_password'] 			= 'forgot_password';
$config['auth']['tpl_reset_password'] 				= 'new_password';



/* End of file auth.php */