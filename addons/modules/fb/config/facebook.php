<?php

// Develop
if (ENVIRONMENT == 'development' or ENVIRONMENT == 'local')
{
	$config['facebook_app_id'] 		= '219580264719633';
	$config['facebook_api_key'] 	= '0e8ee9e961093a339bd9d623f654ea6f';
	$config['facebook_api_secret'] 	= 'bcec92992498ca411b0e1195452d2ca1';
	$config['facebook_site_name'] 	= 'Mudra Kupovina DEV';
}

// Live
elseif (ENVIRONMENT == 'production')
{
	$config['facebook_app_id'] 		= '132507636793752';
	$config['facebook_api_key'] 	= '0e8ee9e961093a339bd9d623f654ea6f';
	$config['facebook_api_secret'] 	= '7db6e01faf94d6a8aa6f9d515a439536';
	$config['facebook_site_name'] 	= 'Mudra Kupovina';
}

$config['facebook_admins']		= '834197248';
