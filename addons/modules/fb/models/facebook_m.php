<?php

class Facebook_m extends CI_Model {
	
	protected $cfg;
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Get config
		$this->config->load('fb/facebook');
		
		// Add config to model
		$this->cfg = array(
                        'appId'  => config_item('facebook_app_id'),
                        'secret' => config_item('facebook_api_secret'),
                        'cookie' => true, // enable optional cookie support
                        );
		
		// And load the library
		$this->load->library('fb/facebook', $this->cfg);
		$this->load->helper('fb/facebook');
		
		// Get user session
		$session = $this->facebook->getSession();
		
		// User data
		$me  = null;
		$uid = null;
		
		// Try to get the data
		if ($session)
		{
			try
			{
				$uid = $this->facebook->getUser();
				$me  = $this->facebook->api('/me?fields=id,name,first_name,last_name,link,email,birthday,picture');
				
				// Update user avatar
				$user = $this->user_m->get_by(array('facebook_id'=>$uid));
				if ($user) $this->user_meta_m->update($user->id, array('avatar'=>@$me['picture']));
			}
			catch (FacebookApiException $e)
			{
				 error_log($e);
			}
		}
		
		if ($me)
		{
			$this->session->set_userdata('fbme',  $me);
			$this->session->set_userdata('fbuid', $uid);
			$this->_create_db_user();
		}
		
	} // __construct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_user()
	{
		return $this->session->userdata('fbme');
		
	} // get_user()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function _create_db_user()
	{
		if (fb_logged_in()) {
			// Get the FB data
			$user  = $this->get_user();
			
			if ($user)
			{
				// Check if user is already in database
				$in_db = $this->user_m->get_by('facebook_id', $user['id']);
				
				if ( ! $in_db)
				{
					// Prepare data
					$user_key = sha1(microtime());
					$db_data = array
					(
						'facebook_id' => $user['id'],
						'username'    => $user['email'],
						'password'    => Auth::encrypt_string(Auth::generate_salt(), $user_key),
						'login_key'   => $user_key,
						'email'       => $user['email'],
						'ip_address'  => $this->input->ip_address(),
						'level'       => 1,
						'loggedin_at' => now(),
					);
					
					// Insert to DB
					$db_data['id'] = $this->user_m->insert($db_data);
					
					// User meta data
					$db_meta = array
					(
						'user_id'      => $db_data['id'],
						'first_name'   => $user['first_name'],
						'last_name'    => $user['last_name'],
						'display_name' => $user['name'],
						'birthday'     => strtotime($user['birthday']),
						'avatar'       => $user['picture'],
					);
					
					// Insert meta to DB
					$this->user_meta_m->insert($db_meta);
				}
				
				// Login
				if ( ! logged_in() and $in_db)
				{
					$db_data = (array) $in_db;
					$this->session->set_userdata(array('userid'    => $db_data['id']));
					$this->session->set_userdata(array('username'  => $db_data['email']));
					$this->session->set_userdata(array('userlevel' => $db_data['level']));
					$this->session->set_userdata(array('userkey'   => $db_data['login_key']));
				}
			}
		}
		
	} // _create_db_user()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
}