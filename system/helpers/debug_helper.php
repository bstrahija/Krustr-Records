<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Debug helper
 *
 * A couple of functions for debuging
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija
 * @version 	0.1
 * 
 */



/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function dump()
{
    list($callee) = debug_backtrace();
	$arguments = func_get_args();
	$total_arguments = count($arguments);

	echo '<div style="background: #EEE !important; border:1px solid #666; padding:10px;">';
	echo '<h1 style="border-bottom: 1px solid #CCC; padding: 0 0 5px 0; margin: 0 0 5px 0; font: bold 18px sans-serif;">'.$callee['file'].' @ line: '.$callee['line'].'</h1><pre>';
	$i = 0;
	foreach ($arguments as $argument)
	{
		echo '<strong>Debug #'.(++$i).' of '.$total_arguments.'</strong>:<br />';
		var_dump($argument);
		echo '<br />';
	}

	echo "</pre>";
	echo "</div>";
}


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function epr($var = null)
{
	if ($var) {
		echo '<pre>'; print_r($var); echo '</pre>';
	} // end if
	
} // end epr()


/* ------------------------------------------------------------------------------------------ */



/* End of file krustr_helper.php */