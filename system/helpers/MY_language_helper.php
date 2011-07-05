<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2010, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Language Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/language_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Lang
 *
 * Fetches a language variable and optionally outputs a form label
 *
 * @access	public
 * @param	string	the language line
 * @param	array	an array of variables and values
 * @param	string	the id of the form element
 * @return	string
 */	

/*
 * Example
 * 
 * Language file: $lang['welcome'] = 'Hello :name, how are you? Today is :date!';
 * In a view: <?php echo lang('welcome', array(':name' => 'Dan', ':date' => date('l'))); ?>
 */

if ( ! function_exists('lang'))
{
	function lang($line, $vars = array(), $id = '')
	{
		$CI =& get_instance();
		$line = str_replace('-', '_', $line);
		$lang_line = $CI->lang->line($line);

		$lang_line = strtr($lang_line, $vars);

		if ($id != '')
		{
			$lang_line = '<label for="'.$id.'">'.$lang_line."</label>";
		}

		if ($lang_line) return $lang_line;
		else 			return '#! '.$line;
	}
}




/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function change_lang($lang = NULL, $hash = NULL)
{
	$ci =& get_instance();
	
	$set_lang 		= NULL;
	$set_lang_code 	= NULL;
	$default_lang 	= KR_LANG;
	$languages 		= $ci->config->item('langs');
	
	// This will be set
	$set_lang 		= $languages[$lang];
	$set_lang_code 	= $lang;
	
	// Set session variales
	$ci->session->set_userdata('kr_edit_lang',       $set_lang_code);
	$ci->session->set_userdata('kr_edit_lang_title', $set_lang);
	
} // end ()


// ------------------------------------------------------------------------
/* End of file MY_language_helper.php */