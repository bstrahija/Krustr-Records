<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * Parser Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Parser
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/parser.html
 */
class MY_Parser extends CI_Parser {

	public    $l_delim = '{';
	public    $r_delim = '}';
	public    $object;
	protected $mustache;

	/**
	 *  Parse a template
	 *
	 * Parses pseudo-variables contained in the specified template view,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	public function parse($template, $data, $return = FALSE)
	{
		$CI =& get_instance();
		$template = $CI->load->view($template, $data, TRUE);

		return $this->_parse($template, $data, $return);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a String
	 *
	 * Parses pseudo-variables contained in the specified string,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	function parse_string($template, $data, $return = FALSE)
	{
		return $this->_parse($template, $data, $return);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a template
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	function _parse($template, $data, $return = FALSE)
	{
		$CI =& get_instance();
		
		// Start benchmark
		$CI->benchmark->mark('Mustache_parser_start');
		
		// Data container
		$data = object_to_array($data);

		if ($template == '')
		{
			return FALSE;
		}

		// Mustache awesomness
		$mustache = new Krustache($data);
		$template = $mustache->render('{{%DOT-NOTATION}}'.$template, $data);

		// Finish benchmark
		$CI->benchmark->mark('Mustache_parser_end');

		if ($return == FALSE)
		{
			$CI->output->append_output($template);
		}

		return $template;
	}


} // end MY_Parser




/* ------------------------------------------------------------------------------------------ */
/* Get a mustache */
/* ------------------------------------------------------------------------------------------ */

require APPPATH."third_party/mustache/mustache.php";



class Krustache extends Mustache {
	
	protected $ci, $data;
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function __construct($data = array())
	{
		$this->ci   =& get_instance();
		$this->data =  $data;
		$this->ci->load->helper('template');
		
	} // end __construct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function _getPartial($tag_name)
	{
		$base = realpath(BASEPATH.'/../../');
		
		// Check root
		$view = reduce_double_slashes($this->data['theme']['path'].'/'.$tag_name);
		
		if (file_exists($base.'/'.$view.'.php'))
		{
			return $this->ci->parser->parse('../../'.$view, $this->data, true);
		}
		
		
		// Check core
		$view = reduce_double_slashes($this->data['theme']['path'].'/partial_core/'.$tag_name);
		
		if (file_exists($base.'/'.$view.'.php'))
		{
			return $this->ci->parser->parse('../../'.$view, $this->data, true);
		}
		
		
		// Check partial
		$view = reduce_double_slashes($this->data['theme']['path'].'/partial/'.$tag_name);
		
		if (file_exists($base.'/'.$view.'.php'))
		{
			return $this->ci->parser->parse('../../'.$view, $this->data, true);
		}
		
	} // end _getPartial()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function _customMethod($tag)
	{
		$tag    = trim($tag);
		$tag    = explode(" ", $tag);
		$func   = @$tag[0];
		$params = @explode("|", $tag[1]);
		
		if ($func) {
			$m = new Mustache_data;
			
			// First check if method exists in mustache class
			if (method_exists($m, $func))
			{
				return @$m->{$func}($params);
			}
			
			// Then check in helpers (only some function are available this way)
			elseif (function_exists($func))
			{
				switch ($func)
				{
					case 'title':
						return call_user_func($func, null, true);
						break;
					
					case 'body':
						return call_user_func($func, null, true, true);
						break;
					
				} // end switch
				
			} // end if
			
		} // end if
		
		return null;
		
	} // end _customMethod()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function _renderMethod($tag_name)
	{
		$tag_name = str_replace("f:", "f_", $tag_name);
		
		// Get the tag
		$tag = explode(" ", $tag_name);
		$tag = $tag[0];
		
		// And the attributes
		$attrs   = array();
		$element = new SimpleXMLElement("<".$tag_name." />");
		foreach ($element->attributes() as $key=>$val)
		{
			$attrs[(string) $key] = (string) $val;
		}
		array_walk($attrs, create_function('&$val', '$val = trim($val);'));
		
		// Get tag value
		$value  = $this->_renderEscaped($tag);
		
		// Method
		$method = (isset($attrs['action'])) ? @$attrs['action'] : null;
		unset($attrs['action']);
		
		// Try to run the method
		if ($method and $attrs)
		{
			try { return call_user_func_array("KR_Template_Helper::".$method, array($value, $attrs)); } catch(Exception $e) { return ''; }
		}
		elseif ($method)
		{
			try { return call_user_func("KR_Template_Helper::".$method, $value); } catch(Exception $e) { return ''; }
		}
		
		return null;
		
	} // end _renderMethod()

	
	
	
	/* ------------------------------------------------------------------------------------------ */

} // end Krustache



/* End of file Parser.php */