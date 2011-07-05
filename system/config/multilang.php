<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Multilang stup
 *
 */
$config['multilang']    = false;
$config['default_lang'] = 'hr'; // The default language
$config['langs']        = array(
	'hr' => 'Hrvatski',
	'en' => 'English',
);
if ( ! defined('KR_LANG')) define('KR_LANG', $config['default_lang']);

