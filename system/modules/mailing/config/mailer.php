<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['mailer'] = array
(
	'template_dir' => 'addons/mail',
	
	'protocol'     => 'mail',
	'mailtype'     => 'html',
	'charset'      => 'utf-8',
	'wordwrap'     => true,
	
	'from'         => 'info@mudrakupovina.hr',
	'from_name'    => 'Mudra kupovina',
	'auto_bcc'     => null,            // Every mail is sent ass BCC to this address (disabled if false)
	'debug'        => false,           // If this is set to true, reports for all actions involving sending emails is sent to a email address
	'debug_email'  => array('boris@creolab.hr'),

);