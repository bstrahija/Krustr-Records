<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* CodeIgniter Gravatar Helper
*
* @package      CodeIgniter
* @subpackage   Helpers
* @category     Helpers
* @author       David Cassidy
*/

function gravatar( $email, $rating = 'X', $size = '80', $default = 'http://gravatar.com/avatar.php' ) {
	// Hash the email address
	$email = md5($email);
	
	# Return the generated URL
	return "http://gravatar.com/avatar.php?gravatar_id="
		.$email."&amp;rating="
		.$rating."&amp;size="
		.$size."&amp;default="
		.$default;
} //end gravatar()

/* End of file gravatar_helper.php */