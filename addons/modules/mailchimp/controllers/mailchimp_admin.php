<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * MailChimp Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.1
 */

class Mailchimp_admin extends Backend {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Restricted access
		Auth::restrict("admin");
		
		// Load some resources
		$this->config->load('mcapi');
		$this->load->library('MCAPI');
		//include(__DIR__.'/../libraries/MCAPI.class.php');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		$this->view = false;
		
		$api = new MCAPI(config_item('mcapi_apikey'));
		
		$retval = $api->campaigns();
		echo '<pre>';
if ($api->errorCode){
	echo "Unable to Pull list of Campaign!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
    echo sizeof($retval['total'])." Total Campaigns Matched.\n";
    echo sizeof($retval['data'])." Total Campaigns returned:\n";
    foreach($retval['data'] as $c){
        echo "Campaign Id: ".$c['id']." - ".$c['title']."\n";
        echo "\tStatus: ".$c['status']." - type = ".$c['type']."\n";
        echo "\tsent: ".$c['send_time']." to ".$c['emails_sent']." members\n";
    }
}
echo '</pre>';
		
		
		//dump( $this->mcapi->campaignAnalytics(config_item('mcapi_apikey'), 885593) );
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Mailchimp_admin


/* End of file mailchimp_admin.php */