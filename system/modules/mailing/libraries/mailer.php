<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	

/**
 * Mailer Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Mailer extends CMS {
	
	private static $ci; // CI object
	private static $c;  // Config container
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		self::$ci =& get_instance();
		
		// Load required resources
		$this->load->model('users/user_m');
		$this->load->library('email');
		$this->load->library('mailing/simple_html_dom');
		$this->load->config('mailing/mailer');
		
		// Load configuration into library
		self::_load_configuration();
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function send($email = null, $template = null, $data = null, $from = null, $subject = null, $debug = false)
	{
		// Load template
		$template = self::$c->template_dir.$template;
		$template = self::$ci->load->view($template, $data, true);
		
		// Check if subject is set in the $data variable, alse parse the template
		if ($subject)
		{
			$title = $subject;
		}
		elseif (isset($data['subject']) and $data['subject']) 
		{
			$title = $data['subject'];
		}
		else
		{
			$html  = str_get_html($template);
			$title = $html->find('title');
			$title = @strip_tags($title[0]);
		}
		
		// Set from
		if ($from) self::$ci->email->from(self::$c->from);
		else       self::$ci->email->from(self::$c->from, self::$c->from_name);

		// Set to
		self::$ci->email->to($email); 

		// Message
		self::$ci->email->subject($title);
		self::$ci->email->message($template);	
		
		// Send or preview
		if ($debug == 'preview')
		{
			echo $template;
		}
		else
		{
			// And send it
			self::$ci->email->send();
			
			// Debug
			if ($debug == 'debug') echo self::$ci->email->print_debugger();
		}
		
	} // send()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function preview($email = null, $template = null, $data = null, $from = null, $subject = null)
	{
		echo self::send($email, $template, $data, $from, $subject, 'preview');
		
	} // preview()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function send_debug($email = null, $template = null, $data = null, $from = null, $subject = null)
	{
		echo self::send($email, $template, $data, $from, $subject, 'debug');
		
	} // end send_debug()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private static function _load_configuration()
	{
		self::$c = new stdClass();
		
		// Get configuration
		$options = self::$ci->config->item('mailer');
		
		if ($options)
		{
			foreach ($options as $key=>$val)
			{
				self::$c->$key = $val;
			}
		}
		
		// Setup template dir
		self::$c->template_dir = reduce_double_slashes('../../'.self::$c->template_dir.'/');
		
		// Configure email class
		$config['protocol'] = self::$c->protocol;
		$config['charset']  = self::$c->charset;
		$config['wordwrap'] = self::$c->wordwrap;
		$config['mailtype'] = self::$c->mailtype;
		self::$ci->email->initialize($config);
		
	} // _load_configuration()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Mailer


/* End of file mailer.php */