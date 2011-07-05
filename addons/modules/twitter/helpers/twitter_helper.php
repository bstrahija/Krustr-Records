<?php

function tweet_autolink($text = '')
{
	// URLs
	$pattern = "/(((http[s]?:\/\/)|(www\.))(([a-z0-9]+\.)?[a-z0-9]+\.[a-z]+(\.[a-z]{2,2})?)\/?[a-z0-9._\/~#&=;%+?-]+[a-z0-9\/#=?]{1,1})/is";
	$text    = preg_replace($pattern, ' <a href="$1">$1</a>', $text);
	
	// Fix URLs without protocols
	$text    = preg_replace('/href="www/i', 'href="http://www', $text);
	
	// Auto link @user
	$pattern = "/(\s|^)@([a-z0-9]{1,15})/i";
	$text    = preg_replace($pattern, '$1@<a href="http://twitter.com/$2">$2</a>', $text);
	
	// Auto link #tag
	$pattern = "/\s(#[a-z0-9.-_]+)/i";
	$text    = preg_replace($pattern, ' <a href="http://twitter.com/search?q=$1">$1</a>', $text);
	
	// Auto link email addresses
	$pattern = "/([a-z0-9\.-_]+@[a-z0-9-_]+\.[a-z\.]+)/i";
	$text    = preg_replace($pattern, ' <a href="mailto:$1">$1</a>', $text);
	
	return $text;
	
} // end tweet_autolink()


function twitter_timeline($username = null, $num = 5)
{
	// The tweet container
	$tweets = array();
	
	if (function_exists('simplexml_load_file')) {
		// Get XML for timeline
		$xml = simplexml_load_file("http://twitter.com/statuses/user_timeline/".$username.".xml");
		
		if ($xml) {
			$i=0;
			foreach ($xml->status as $status) {
				$tweet = new stdClass();
				$tweet->text            = auto_link((string) $status->text);
				$tweet->when            = strtotime((string) $status->created_at);
				$tweet->url             = "http://twitter.com/".$username."/status/".(string) $status->id;
				$tweet->source          = (string) $status->source;
				$tweet->author          = (string) $status->user->name;
				$tweets[] = $tweet; $i++;
				
				if ($i >= $num) break;
			} // end foreach
			
			return $tweets;
		} // end if
	}
	
	else {
		return 'ERROR: simplexml_load_file() is not supported.';
		
	} // end if
	
	return null;
        
} //end twitter_timeline()