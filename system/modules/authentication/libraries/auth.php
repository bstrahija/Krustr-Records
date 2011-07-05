<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Auth Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.9
 *
 */

class Auth extends Core {
	
	private static $ci; // CI object
	private static $c;  // Config container
	
	// Users
	private static $_user_cache 	= array();
	
	// Errors and messages
	private static $_errors 		= array();
	private static $_messages 		= array();
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		self::$ci =& get_instance();
		
		// Load some required resources
		self::$ci->load->database();
		self::$ci->config->load('authentication/auth');
		self::$ci->load->library('encrypt');
		self::$ci->load->library('email');
		self::$ci->load->library('session');
		self::$ci->load->library('form_validation');
		self::$ci->load->library('notice');
		self::$ci->load->helper('authentication/auth');
		self::$ci->load->helper('authentication/gravatar');
		self::$ci->load->helper('cookie');
		self::$ci->load->helper('string');
		self::$ci->load->model('users/user_m');
		self::$ci->load->model('users/user_meta_m');
		
		// Load configuration into library
		self::_load_configuration();
		
		// Autologin
		self::_cookie_log_in();
		
	} // __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function log_in($identity = null, $password = null, $remember = false, $hashed = false)
	{
		// Login with username, email or either
		$login_with = self::$c->login_with;
		
		// Get the user
		if ($login_with == 'both')
		{
			$user = self::get_user($identity, 'username');
			
			if ( ! $user)
			{
				$user = self::get_user($identity, 'email');
			}
		}
		else
		{
			$user = self::get_user($identity, self::$c->identity);
		}
		
		// If user is found compare the password
		if ($user)
		{
			// Encrypt the password for comparison
			if ($hashed === false) $password = self::encrypt_string($password, $user->login_key);
			
			// Check password and user status
			if ( ! $user->activation_key and $user->status == 'active' and $user->password === $password)
			{
				// Update last login
				self::$ci->user_m->update($user->id, array
				(
					'loggedin_at' => time(),
					'ip_address'  => self::$ci->input->ip_address(),
				));
				
				// Remember me if enabled
				self::_set_login_cookies($user);
				
				// Create auth session
				self::_set_login_session($user);
				
				return true;
			}
		}
		
		return false;
		
	} // log_in()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private static function _cookie_log_in()
	{
		// Try to autologin if not logged in already
		if ( ! self::logged_in())
		{
			$identity   = self::$ci->encrypt->decode(get_cookie('identity'));
			$cookie_key = self::$ci->encrypt->decode(get_cookie(self::$c->cookie_name));
			
			// Try to find the user
			if ($identity and $cookie_key)
			{
				$user = self::get_user('remember_key', $cookie_key);
				
				// Verify the data
				if ($user and isset($user->{self::$c->identity}) and $user->{self::$c->identity} == $identity)
				{
					// Save session
					self::_set_login_cookies($user);
					self::_set_login_session($user);
				}
			}
		}
		
		return null;
		
	} // _cookie_log_in()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private static function _set_login_session($user = null)
	{
		if ($user)
		{
			$user_data = array
			(
				'userid'          => $user->id,
				'username'        => $user->username,
				'userdisplayname' => $user->display_name,
				'userlevel'       => $user->level,
				'userkey'         => $user->login_key,
			);
			
			self::$ci->session->set_userdata($user_data);
		}
		
	} // _set_login_session()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private static function _set_login_cookies($user = null)
	{
		// Remember me if enabled
		if (self::$c->remember_always or (self::$c->remember_enable and self::$ci->input->post('remember_me') == 1))
		{
			// And create the remember cookies
			$cookie = array
			(
				'name'   => 'identity',
				'value'  => self::$ci->encrypt->encode($user->{self::$c->identity}),
				'expire' => self::$c->cookie_expires,
			);
			set_cookie($cookie);
			
			$cookie = array
			(
				'name'   => self::$c->cookie_name,
				'value'  => self::$ci->encrypt->encode(hash(self::$c->hash_algo, $user->password)),
				'expire' => self::$c->cookie_expires,
			);
			set_cookie($cookie);
		}
		
	} // _set_login_cookies()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function log_out()
	{
		// Delete all cookies
		delete_cookie(self::$c->cookie_name);
		delete_cookie('identity');
		
		// And the session data
		self::$ci->session->unset_userdata('userid');
		self::$ci->session->unset_userdata('userkey');
		self::$ci->session->unset_userdata('userlevel');
		self::$ci->session->unset_userdata('userdisplay_name');
		self::$ci->session->unset_userdata('username');
		
	} // log_out()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function register($data = null, $meta_data = null, $send_activation = true)
	{
		// Prepare data
		if ( ! $data)
		{
			$data['username']   = self::$ci->input->post('username');
			$data['email']      = self::$ci->input->post('email');
			$data['login_key']  = self::generate_salt();
			$data['password']   = self::encrypt_string(self::$ci->input->post('password'), $data['login_key']);
			$data['ip_address'] = self::$ci->input->ip_address();
			$data['level']      = self::$c->default_level;
			
			// Activation
			if (self::$c->email_activation and $send_activation)
			{
				$data['activation_key'] = self::encrypt_string(time(), self::generate_salt());
				$data['status']         = 'inactive';
			}
		}
		else
		{
			// Add some required data
			$data['login_key']  = self::generate_salt();
			$data['password']   = self::encrypt_string($data['password'], $data['login_key']);
			$data['ip_address'] = self::$ci->input->ip_address();
			$data['level']      = self::$c->default_level;
			
			// Activation
			if (self::$c->email_activation and $send_activation)
			{
				$data['activation_key'] = self::encrypt_string(time(), self::generate_salt());
				$data['status'] 		= 'inactive';
			}
		}
		
		// Now store the data
		if (self::$ci->user_m->is_username_unique($data['username']) and self::$ci->user_m->is_email_unique($data['email'] ))
		{
			$data['user_id'] = $user_id = self::$ci->user_m->insert($data);
			
			// Meta data
			$meta_data['user_id'] = $user_id;
			
			foreach (self::$c->meta_columns as $name=>$options)
			{
				if ( ! isset($meta_data[$name]) and self::$ci->input->post($name)) $meta_data[$name] = self::$ci->input->post($name);
			}
			
			self::$ci->user_meta_m->insert($meta_data);
			
			// Activation email
			if ($send_activation) self::_send_activation_email($data);
			
			return $data['user_id'];
			
		}
		else
		{
			self::$ci->form_validation->_error_array['custom_error'] = 'Username or email already exist in our database.';
			
		}
		
		return false;
		
	} // register()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function update_user($id = null, $data = null, $meta_data = null)
	{
		// This will be updated to the DB
		$db_data = array();
		
		// Get the user
		if ( ! $id) $user = self::get_user();
		else 		$user = self::get_user($id);
		
		// Prepare password change
		if ((isset($data['password']) and $data['password']) or (self::$ci->input->post('password')))
		{
			$old_password           = isset($data['password_old']) ? $data['password_old'] : self::$ci->input->post('password_old');
			$old_password_encrypted = self::encrypt_string($old_password, $user->login_key);
			$new_password           = isset($data['password']) ? $data['password'] : self::$ci->input->post('password');
			
			// Check if old password matches the one in the DB
			if ($old_password_encrypted == $user->password)
			{
				$db_data['login_key'] = $user->login_key = self::generate_salt();
				$db_data['password']  = self::encrypt_string($new_password, $db_data['login_key']);
			}
		}
		
		// Prepare basic data and update it
		$db_data['username'] 	= isset($data['username']) 	? $data['username'] : self::$ci->input->post('username');
		$db_data['email'] 		= isset($data['email']) 	? $data['email'] 	: self::$ci->input->post('email');
		self::$ci->user_m->update($user->id, $db_data);
		
		// Now change the password
		if (isset($new_password))
		{
			self::_set_login_session($user);
			self::_set_login_cookies($user);
		}
		
		// Prepare meta data
		foreach (self::$c->meta_columns as $name=>$options)
		{
			$db_meta_data[$name] = isset($data[$name]) ? $data[$name] : self::$ci->input->post($name);
		}
		
		self::$ci->user_meta_m->update_by(array('user_id'=>$user->id), $db_meta_data);
		
		return true;
		
	} // update_user()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private static function _send_activation_email($data = null)
	{
		if (self::$c->email_activation)
		{
			$activation_data = array
			(
				'identity'   		=> $data[self::$c->identity],
				'id'         		=> $data['user_id'],
				'email'      		=> $data['email'],
				'activation_key' 	=> $data['activation_key'],
			);
			$activation_message = self::$ci->load->view(self::$c->email_tpl_path.'/'.self::$c->tpl_activate, $activation_data, true);
			
			self::$ci->email->clear();
			self::$ci->email->initialize(array
			(
				'protocol' => self::$c->email_protocol,
				'mailtype' => self::$c->email_type,
			));
			self::$ci->email->from(self::$c->admin_email, self::$c->admin_name);
			self::$ci->email->to($data['email']);
			self::$ci->email->subject('Mudra kupovina - Aktivacija korisničkog računa za "'.$data[self::$c->identity]).'"';
			self::$ci->email->message($activation_message);
			
			if (self::$ci->email->send() == true)
			{
				return true;
			}
			else
			{
				echo self::$ci->email->print_debugger();
				return false;
			}
		}
		elseif ($data['user_id'])
		{
			return true;
			
		}
		
	} // _send_activation_email()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function activate($user_id = null, $activation_key = null, $login_after = true)
	{
		if ($user_id)
		{
			$user = self::get_user($user_id);
			
			if ($user)
			{
				// Change status, and delete activation key
				$activation = self::$ci->user_m->update_by(array
				(
					'id'             => $user_id,
					'activation_key' => $activation_key,
				), 
				array
				(
					'status'         => 'active',
					'activation_key' => '',
				));
				
				// Automaticly login user if needed
				if ($activation and $login_after)
				{
					self::_set_login_session($user);
					self::_set_login_cookies($user);
					
					return true;
				}
			}
		}
		
		return false;
		
	} // activate()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function deactivate($user_id = null)
	{
		
	} // end deactivate()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function send_reset_email($email = null)
	{
		if ($email and ! self::$ci->user_m->is_email_unique($email))
		{
			$user = self::get_user($email, 'email');
			
			$reset_data = array
			(
				'identity'     => $user->{self::$c->identity},
				'id'           => $user->id,
				'email'        => $user->email,
				'reminder_key' => self::generate_salt(),
			);
			self::$ci->user_m->update($user->id, array('reminder_key'=>$reset_data['reminder_key']));
			$reset_message = self::$ci->load->view(self::$c->email_tpl_path.'/'.self::$c->tpl_forgot_password, $reset_data, true);
			
			self::$ci->email->clear();
			self::$ci->email->initialize(array
			(
				'protocol' => self::$c->email_protocol,
				'mailtype' => self::$c->email_type,
			));
			self::$ci->email->from(self::$c->admin_email, self::$c->admin_name);
			self::$ci->email->to($reset_data['email']);
			self::$ci->email->subject('Mudra kupovina - Zaboravljena lozinka - 1. korak');
			self::$ci->email->message($reset_message);
			
			if (self::$ci->email->send() == true)
			{
				return true;
			}
			else
			{
				echo self::$ci->email->print_debugger();
				return false;
			}
		}
		
		return false;
		
	} // send_reset_email()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function reset_password($reminder_key = null)
	{
		if ($reminder_key)
		{
			$user = self::$ci->user_m->get_by('reminder_key', $reminder_key);
			$user = self::get_user($user->id);
			
			if ($user)
			{
				$identity = self::$c->identity;
				$data = array();
				$data['identity']               = $user->identity;
				$data['email']                  = $user->email;
				$data['new_login_key']          = self::generate_salt();
				$data['new_password']           = strtolower(random_string('alnum', 6));
				$data['new_encrypted_password'] = self::encrypt_string($data['new_password'], $data['new_login_key']);
				
				// Update data in database
				self::$ci->user_m->update($user->id, array
				(
					'login_key' => $data['new_login_key'],
					'password'  => $data['new_encrypted_password'],
				));
				
				// Send email
				self::send_new_password($data);
				
				return true;
			}
		}
		
		return false;
		
	} // reset_password()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function send_new_password($data = null)
	{
		if ($data)
		{
			$user = self::get_user($data['email'], 'email');
			
			$reset_data = array
			(
				'identity'     => $user->{self::$c->identity},
				'id'           => $user->id,
				'email'        => $user->email,
				'new_password' => $data['new_password'],
			);
			$reset_message = self::$ci->load->view(self::$c->email_tpl_path.'/'.self::$c->tpl_reset_password, $reset_data, true);
			
			self::$ci->email->clear();
			self::$ci->email->initialize(array
			(
				'protocol' => self::$c->email_protocol,
				'mailtype' => self::$c->email_type,
			));
			self::$ci->email->from(self::$c->admin_email, self::$c->admin_name);
			self::$ci->email->to($reset_data['email']);
			self::$ci->email->subject('Mudra kupovina - Zaboravljena lozinka - 2. korak');
			self::$ci->email->message($reset_message);
			
			if (self::$ci->email->send() == true)
			{
				return true;
			}
			else
			{
				echo self::$ci->email->print_debugger();
				return false;
			}
		}
		
		return false;
		
	} // send_new_password()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function change_password($identity = null, $old_password = null, $new_password = null)
	{
		$user = self::get_user($identity, self::$c->identity);
		
	} // end change_password()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !===> User data */
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function get_user($key = null, $get_by = 'id')
	{
		if ( ! $key) $key = self::$ci->session->userdata('userid');
		
		if ($get_by == 'id')
		{
			if (isset(self::$_user_cache[$key]))
			{
				return self::$_user_cache[$key];
			}
			else
			{
				$user = self::$ci->user_m->get_extended($key);
				
				if ($user)
				{
					self::$_user_cache[$key] = $user;
					return $user;
				}
			}
		}
		else
		{
			$user = self::$ci->user_m->get_extended($key, $get_by);
			
			if ($user)
			{
				self::$_user_cache[$user->id] = $user;
				return $user;
			}
		}
		
		return null;
		
	} // get_user()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Get a user value
	 *
	 */
	public static function get_user_var($var = 'username', $key = null, $get_by = 'id')
	{
		$user = self::get_user($key, $get_by);
		if ($user and isset($user->$var)) 	return $user->$var;
		else 								return null;
		
	} // get_user_var()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Get the avatar either from the DB uploaded image, or gravatar
	 * If $tag is true, then an entire <img> tag is returned
	 *
	 */
	public static function get_user_avatar($user_id = null, $tag = false, $width = 32, $height = 32, $dummy = 'uploads/avatars/dummy.png')
	{
		// First get user data
		$user = self::get_user($user_id);
		
		if (isset($user->avatar) and $user->avatar == '-')
		{
			return site_url($dummy);
		}
		
		// Regular uploaded avatar
		elseif (isset($user->avatar) and $user->avatar)
		{
			return site_url($user->avatar);
		}
		
		// Requires the facebook library
		elseif ($user->facebook_id)
		{
			return fb_picture($user->facebook_id);
		}
		
		// Gravatar		
		elseif ($user->email)
		{
			$gravatar = gravatar($user->email, 'X', $width, site_url($dummy));
			return $gravatar;
		
		}
		
		return site_url($dummy);
		
	} // get_user_avatar()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function logged_in()
	{
		$user_id  = self::$ci->session->userdata('userid');
		$user_key = self::$ci->session->userdata('userkey');
		
		if ($user_id)
		{
			$user = self::get_user($user_id);
			
			if ($user and $user_key === $user->login_key)
			{
				return true;
			}
		}
		
		return false;
		
	} // logged_in()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function is_admin($id = null)
	{
		$user = self::get_user($id);
		
		if ($user and $user->level >= self::$c->user_groups['admin']) return true;
		
		return false;
		
	} // is_admin()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function is_superadmin($id = null)
	{
		$user = self::get_user($id);
		
		if ($user and $user->level >= self::$c->user_groups['superadmin']) return true;
		
		return false;
		
	} // is_superadmin()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function restrict($role = 'user')
	{
		// Restriction flag
		$restricted = true;
		
		// Redirect to login page if not logged in
		if ( ! logged_in())
		{
			redirect(self::$c->login_page);
		}
		else
		{
			//$role = get_user
			if (self::user_has_access($role))
			{
				$restricted = false;
			}
		}
		
		// Exit if no access
		if ($restricted) exit("You are not authorized to view this page.");
		
	} // restrict()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function user_has_access($role = 'admin', $user_id = null)
	{
		if ($user_id) $user_level = self::get_user_var('level');
		else          $user_level = self::get_user_var('level', $user_id);
		
		// Get required level
		if (isset(self::$c->user_groups[$role])) $required_level = self::$c->user_groups[$role];
		else                                  $required_level = self::$c->user_groups['admin'];
		
		if ($user_level >= $required_level) return true;
		
		return false;
		
	} // user_has_access()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !===> Misc */
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private static function _load_configuration()
	{
		self::$c = new stdClass();
		
		// Get configuration
		$options = self::$ci->config->item('auth');
		
		if ($options)
		{
			foreach ($options as $key=>$val)
			{
				self::$c->$key = $val;
			}
		}
		
		// If identity is 'both', set to 'username'
		if (self::$c->identity == 'both')
		{
			self::$c->login_with = 'both';
			self::$c->identity   = 'username';
		}
		else
		{
			self::$c->login_with = self::$c->identity;
		}
		
	} // _load_configuration()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Creates a referral key if it doesn't exist for a user
	 *
	 */
	public static function create_referral_key($user_id = null)
	{
		if ( ! $user_id) $user_id = self::$ci->session->userdata('userid');
		
		if ($user_id)
		{
			$referral_key = self::generate_salt();
			
			// Update to database
			self::$ci->user_m->update($user_id, array('referral_key'=>$referral_key));
			
			return $referral_key;
		}
		
		return null;
		
	} // end ()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Encrypts a string (password)
	 */
	public static function encrypt_string($string = null, $salt = null)
	{
		if ( ! $salt) $salt = self::generate_salt();
		
		$encrypted = hash(self::$c->hash_algo, self::$c->salt.$salt.$string);
		
		return $encrypted;
		
	} // encrypt_string()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Generates a salt string for password encryption
	 *
	 */
	public static function generate_salt()
	{
		return hash(self::$c->hash_algo, time().random_string('alnum', 32).uniqid());
		
	} // generate_salt()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function add_error($error = null)
	{
		if ($error) self::$_errors[] = $error;
		
		return $error;
		
	} // error()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function get_errors()
	{
		return self::$_errors;
		
	} // get_errors()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !==> Install */
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function install()
	{
		// Load DB forge library
		self::$ci->load->dbforge();
		
		// Drop existing tables
		self::$ci->dbforge->drop_table(self::$ci->db->dbprefix(self::$user_meta_table));
		self::$ci->dbforge->drop_table(self::$ci->db->dbprefix(self::$users_table));
		
		// Create users table
		self::$ci->db->query("CREATE TABLE `".self::$ci->db->dbprefix(self::$users_table)."` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `username` varchar(150) NOT NULL,
		  `email` varchar(150) NOT NULL,
		  `password` varchar(150) NOT NULL,
		  `level` int(11) NOT NULL DEFAULT '1',
		  `login_key` varchar(150) NOT NULL,
		  `remember_key` varchar(150) DEFAULT NULL,
		  `activation_key` varchar(150) DEFAULT NULL,
		  `reminder_key` varchar(150) DEFAULT NULL,
		  `status` varchar(20) NOT NULL DEFAULT 'active',
		  `ip_address` varchar(20) DEFAULT NULL,
		  `created_at` int(11) DEFAULT NULL,
		  `updated_at` int(11) DEFAULT NULL,
		  `loggedin_at` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  KEY `username` (`username`),
		  KEY `email` (`email`),
		  KEY `login_key` (`login_key`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		
		// Prepare meta columns
		$meta_columns = '';
		foreach (self::$meta_columns as $name=>$options) {
			$meta_columns .= "`".$name."` ".$options.",";
		} // end foreach
		
		// Create meta table
		self::$ci->db->query("CREATE TABLE `".self::$ci->db->dbprefix(self::$user_meta_table)."` (
		  `user_id` int(11) NOT NULL,
		  ".$meta_columns."
		  PRIMARY KEY (`user_id`),
		  CONSTRAINT `".self::$ci->db->dbprefix(self::$user_meta_table)."_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `".self::$ci->db->dbprefix(self::$users_table)."` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		
		// And add a admin user
		$db_data = array(
			 'username' 	=> 'admin'
			,'login_key' 	=> self::generate_salt()
			,'password' 	=> random_string('alnum', 6)
			,'email' 		=> 'admin@local.net'
		);
		
		// User data
		$user_data = array(
			 'username' 	=> $db_data['username']
			,'password' 	=> self::encrypt_string($db_data['password'], $db_data['login_key'])
			,'email' 		=> $db_data['email']
			,'login_key' 	=> $db_data['login_key']
			,'level' 		=> 100
			,'ip_address' 	=> self::$ci->input->ip_address()
		);
		$user_id = self::$ci->user_m->insert($user_data);
		
		//User meta data
		$user_meta_data = array(
			 'user_id' 		=> $user_id
			,'first_name' 	=> 'Dexter'
			,'last_name' 	=> 'Morgan'
			,'display_name' => 'The Blood Guy'
			,'avatar' 		=> 'dummy.jpg'
			,'bio' 			=> 'Lorem ipsum...'
			,'address' 		=> 'Regent Street 199'
			,'postal_code' 	=> '99666'
			,'city' 		=> 'Miami'
			,'country' 		=> 'USA'
		);
		self::$ci->user_meta_m->insert($user_meta_data);
		
		return $db_data;
		
	} // install()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Auth


/* End of file auth.php */