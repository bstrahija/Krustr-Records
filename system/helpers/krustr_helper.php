<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Krustr helper
 *
 * A couple of functions that are used by the Krustr CMS
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2009, Boris Strahija
 * @version 	0.1
 * 
 */



/* ------------------------------------------------------------------------------------------ */




// *******************************************************
// !/===> Arrays and objects
// *******************************************************

function get_array_value($array, $key) {
	if( isset($array[$key]) && $array[$key] ) return $array[$key];
	else return NULL;
} //end get_array_value()

function get_object_value($object, $key) {
	if( isset($object -> $key) && $object -> $key ) return $object -> $key;
	else return NULL;
} //end get_array_value()


function object_merge($o1, $o2)
{
    return (object) array_merge((array) $o1, (array) $o2);
}

/**
 * Converts a var to array if not already an array
 *
 */
function to_array($data)
{
	return ( ! is_array($data))?array($data):$data;;
	
} //end function_name()


/* ------------------------------------------------------------------------------------------ */

/**
 * Returns the URL of a backend section (similar to site_url)
 *
 */
function admin_url($url = null)
{
	$ci =& get_instance();
	
	return site_url(reduce_double_slashes(config_item('backend_trigger').'/'.$url));
		
} // end admin_url()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function admin_anchor($url = null, $title = null, $attributes = null)
{
	$ci =& get_instance();
	
	return anchor(reduce_double_slashes(config_item('backend_trigger').'/'.$url), $title, $attributes);
		
} // end admin_anchor()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function admin_redirect($url = null)
{
	redirect_admin($url);
	
} // end admin_redirect()


/* ------------------------------------------------------------------------------------------ */

/**
 * Redirects to a backend URL
 *
 */
function redirect_admin($url = null)
{
	$ci =& get_instance();
	
	return redirect(reduce_double_slashes(config_item('backend_trigger').'/'.$url));
		
} // end redirect_admin()


function array_multimerge($a1,$a2)
{
    foreach($a1 as $k => $v) {
        if(!array_key_exists($k,$a2)) continue;
        if(is_array($v) && is_array($a2[$k])){
            $a1[$k] = array_multimerge($v,$a2[$k]);
        }else{
            $a1[$k] = $a2[$k];
        }
    }
    return $a1;
}


/**
 * Find a value in object list by key
 *
 */
function in_object_list($object_list = NULL, $value = NULL, $by_key = 'id') {
	if (is_array($object_list)) :
		foreach ($object_list as $key=>$val) :
			if ($value == $val->{$by_key}) return TRUE;
		endforeach;
	endif;
	
	return FALSE;
	
} //end in_object_list()


/* ------------------------------------------------------------------------------------------ */

/**
 * Order object list by key
 * 
 */
function osort(&$oarray, $p, $order = 'ASC')
{
	if ($order == 'ASC') 	usort($oarray, create_function('$a,$b', 'if ($a->' . $p . '== $b->' . $p .') return 0; return ($a->' . $p . '< $b->' . $p .') ? -1 : 1;'));
	else 					usort($oarray, create_function('$a,$b', 'if ($a->' . $p . '== $b->' . $p .') return 0; return ($a->' . $p . '< $b->' . $p .') ? 1 : -1;'));
	
} //end osort()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function array_to_object($data)
{
	return is_array($data) ? (object) array_map(__FUNCTION__,$data) : $data;
	
} //end array_to_object()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function object_to_array($object = NULL)
{
	if ( ! is_object($object) AND ! is_array($object)) {
		return $object;
	} // end if
	if(is_object($object)) {
		$object = get_object_vars($object);
	} // end if
	return array_map('object_to_array', $object);
	 
} // end ()


/**
 *
 */
function array_push_front(&$arr, $item)
{
  $arr = array_pad($arr, -(count($arr) + 1), $item);
  
} // end ()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function recordset_to_tree($records = null, $parent_id = 0, $level = 0)
{
	$return = array();
	
	foreach ($records as $record) {
		if ($record->parent_id == $parent_id) {
			$record->offset = 1*$level;
			$children = recordset_to_tree($records, $record->id, $level+1);
			if ($children) $record->children = $children;
			$return[] = $record;
		} // end if
	} // end foreach
	
	return $return;
	
} // end recordset_to_tree()



/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function entry_tree($entries = NULL, $parent_id = 0, $level = 0, $type = 'sim')
{
	// Sort the list by parent id
	//osort($entries, 'title');
	
	if ($entries) :
		$tmp_entries = array();
		
		// First let's put the root entries into the tmp array
		foreach ($entries as $entry) :
			if ($entry->parent_id == $parent_id) :
				$entry->offset = 1*$level;
				$tmp_entries[] = $entry;
				
				// And now let's add the childrend underneath
				$child_entries = entry_tree($entries, $entry->id, $level+1);
				$tmp_entries = array_merge($tmp_entries, $child_entries);
				
			endif;
		endforeach;
		
		return $tmp_entries;
	endif;
	
	return NULL;
	
} //end entry_tree()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function tree_to_ul($records = null, $class = 'tree')
{
	if ($class) $html = '<ul class="'.$class.'">';
	else		$html = '<ul>';
	
	foreach ($records as $record) {
		$html .= '<li rel="'.$record->id.'"><div class="item '.$record->status.'" rel="'.$record->id.'"><a href="#" class="title"><em class="picto s globe"></em> '.$record->title.' <i>{'.$record->order_key.'}</i></a></div>';
		
		if (isset($record->children)) $html .= tree_to_ul($record->children, null);
		
		$html .= '</li>';
	} // end foreach
	
	$html .= '</ul>';
	
	return $html;
	
} // end tree_to_ul()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function recordset_to_ul($records = null, $class = 'tree')
{
	if ($class) $html = '<ul class="'.$class.'">';
	else		$html = '<ul>';
	
	foreach ($records as $record) {
		$html .= '<li rel="'.$record->id.'"><div class="item '.$record->status.'" rel="'.$record->id.'"><a href="#" class="title"><em class="picto s globe"></em> '.$record->title.'</a></div>';
		$html .= '</li>';
	} // end foreach
	
	$html .= '</ul>';
	
	return $html;
	
} // end (recordset_to_ul)



/**
 * Extract the file extension
 *
 * @param	string
 * @return	string
 */
function get_extension($filename)
{
	$x = explode('.', $filename);
	return '.'.end($x);
}



/* End of file krustr_helper.php */