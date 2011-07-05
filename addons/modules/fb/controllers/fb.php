<?php

class Fb extends CMS {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		$this->config->load('fb/facebook');
		
		$this->load->library('fb/facebook', array(
			'appId'  => config_item('facebook_app_id'),
			'secret' => config_item('facebook_api_secret'),
			'cookie' => true,
		));
		
		$session = $this->facebook->getSession();

		$me = null;
		
		try {
		    $uid = $this->facebook->getUser();
		    $me = $this->facebook->api('/me');
		  } catch (FacebookApiException $e) {
		    error_log($e);
		  }
		
		dump($uid);
		dump($me);
		
		$this->load->view('fb/fb');
	}
}