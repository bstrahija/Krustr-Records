<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include(APPPATH.'/config/multilang.php');


/**
 * Some email info
 *
 */
$config['email']['from'] 		= 'boris@creolab.hr';
$config['email']['from_name'] 	= 'Boris Strahija';
$config['email']['auto_bcc'] 	= FALSE; // Every mail is sent ass BCC to this address (disabled if false)
$config['email']['type'] 		= 'html';



/**
 * Evironment info
 *
 * Status of the app (dev, test, live)
 * When the status is set to dev, the profiler is enabled
 *
 */
$config['environment'] 		= ENVIRONMENT; // Set in config.php, based on the domain
$config['lang'] 			= 'en';
$config['backend_trigger'] 	= 'backend';



/**
 * Rich text editor (plain / textile / jwysiwyg / ckeditor / tinymce)
 *
 */
$config['rich_editor'] = 'ckeditor';



/**
 * Entry ID obfuscation
 *
 */
$config['tinyo_set'] = 'eURtZWfxXmLE7OF56gqJhunsP3kKvDwjV4BY9Ii8cpyo2TQrlSC1H0AMdzGNba';



/**
 * Basic info
 *
 * Some basic info for the app.
 *
 */
$config['site_name']			= 'Krustr Records';
$config['version']				= '0.4';
$config['copyright_author']		= 'Boris Strahija';
$config['copyright_company']	= 'Creo';
$config['copyright_url']		= 'http://creolab.hr';
$config['copyright_produrl']	= 'http://krustr.net';



/**
 * Akismet anti-spam data
 *
 */
$config['akismet']['key'] 		= 'bf3782d15aff';
$config['akismet']['site_url'] 	= 'http://72.10.52.150';



/**
 * Caching by environment
 *
 */
if ( ! defined('CACHE_DRIVER'))
{
	if     (ENVIRONMENT == 'local')       define('CACHE_DRIVER', 'file');
	elseif (ENVIRONMENT == 'development') define('CACHE_DRIVER', 'file');
	elseif (ENVIRONMENT == 'testing')     define('CACHE_DRIVER', 'file');
	elseif (ENVIRONMENT == 'production')  define('CACHE_DRIVER', 'file');
	else                                  define('CACHE_DRIVER', 'file');
}



/*
 * Upload Paths
 *
 */
$config['uploads']['path'] 					= 'uploads';
$config['uploads']['entry_path'] 			= 'uploads/entries';
$config['uploads']['entry_gallery_path'] 	= 'uploads/entry_galleries'; // Galleries created in entries
$config['uploads']['gallery_path'] 			= 'uploads/galleries';
$config['uploads']['file_path'] 			= 'uploads/files';
$config['uploads']['tmp_path'] 				= 'uploads/tmp';



/* End of file krustr.php */