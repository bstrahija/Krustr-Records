<?php

/**
 * Transform datepicker date to unix format
 *
 */
function jqdate_to_unix($date = NULL)
{
	if ($date) :
		$darr = explode('/', $date);
		$date = mktime(0, 0, 0, $darr[1], $darr[2], $darr[0]);
	endif;
	
	return $date;
} //end jqdate_to_unix()




/**
 * Transform datepicker date and time to unix format
 *
 */
function jqdatetime_to_unix($datetime = NULL)
{
	$datetime = explode(" ", $datetime);
	$date = $datetime[0];
	$time = $datetime[1];
	
	if ($date) :
		$darr = explode('/', $date);
		$tarr = explode(':', $time);
		
		$datetime = mktime((int)$tarr[0], (int)$tarr[1], 0, (int)$darr[1], (int)$darr[2], (int)$darr[0]);
	endif;
	
	return $datetime;
} //end jqdatetime_to_unix()




/**
 * Transform minutes to hours:minutes
 *
 */
function minutes_to_human($minutes)
{
	if ($minutes) :
		$hours = floor($minutes / 60);
		$minutes = $minutes - ($hours * 60);
		
		$hrmin = sprintf("%02d", $hours).':'.sprintf("%02d", $minutes);
		
		return $hrmin;
	endif;
	
	return $minutes;
} //end jqdate_to_unix()



// Returns array with hours, minutes and seconds from unix time
function get_his_array($time = NULL) {
	$arr = array();
	
	// Hours
	$arr['h'] = intval($time / 3600);
	$time -= $arr['h'] * 3600;
	
	// Minutes
	$arr['i'] = intval($time/60);
	$time -= $arr['i'] * 60;
	
	// Seconds
	$arr['s'] = $time;
	
	return $arr;
}


// Returns array with days, hours, minutes and seconds from unix time
function get_dhis_array($time = NULL) {
	$arr = array();
	
	// Days
	$arr['d'] = intval($time / (3600 * 24));
	$time -= $arr['d'] * (3600 * 24);
	
	// Hours
	$arr['h'] = intval($time / 3600);
	$time -= $arr['h'] * 3600;
	
	// Minutes
	$arr['i'] = intval($time/60);
	$time -= $arr['i'] * 60;
	
	// Seconds
	$arr['s'] = $time;
	
	return $arr;
}