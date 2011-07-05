<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

include(APPPATH.'/config/krustr.php');
define('BACKEND', $config['backend_trigger']);


$route['default_controller'] 	= 'front';


// Field groups
$route[BACKEND.'/fields/groups'] 				= "fields/groups_admin";
$route[BACKEND.'/fields/groups/(:any)'] 		= "fields/groups_admin/$1";
$route[BACKEND.'/fields/groups/(:any)/(:any)'] 	= "fields/groups_admin/$1/$2";


// Content upload
$route[BACKEND.'/upload/(:any)'] 		= "content/upload_admin/$1";
$route[BACKEND.'/upload/(:any)/(:any)'] = "content/upload_admin/$1/$2";


// Admin routes
$route[BACKEND.'/login']                = 'authentication/authentication_admin/login';
$route[BACKEND.'/logout']               = 'authentication/authentication_admin/logout';
$route[BACKEND.'/([a-zA-Z_-]+)/(:any)'] = '$1/$1_admin/$2';
$route[BACKEND.'/([a-zA-Z_-]+)']        = '$1/$1_admin/index';
$route[BACKEND]                         = 'dashboard/dashboard_admin';


// Language routes
global $site_lang;
include(APPPATH.'/config/multilang.php');


if ($config['multilang'] === true)
{
	$all_langs = $config['langs'];
	$langs     = array();
	
	foreach ($all_langs as $lk => $l)
	{
		$langs[] = $lk;
	}
	
	// Get at our first segment after index.php. Might need to adjust. Check your $_SERVER['REQUEST_URI']
	$check = explode('/', $_SERVER['REQUEST_URI']); $check = $check[1];
	
	// See if it's a valig language defined in the config
	if (in_array($check, $langs))
	{
		$site_lang  = $check;
		$valid_lang = true;
	}
	else
	{
		$site_lang  = $config['default_lang'];
		$valid_lang = false;
	}
	
	// Define constant
	define('SITE_LANG', $site_lang);
}
else
{
	define('SITE_LANG', $config['default_lang']);
}


// 404 override to frontend controller
$route['404_override'] 			= 'front';


/* End of file routes.php */
/* Location: ./application/config/routes.php */