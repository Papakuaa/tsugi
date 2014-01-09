<?php

require_once $CFG->dirroot."/lib/lms_lib.php";  // During transition

// Check if we already have a COOKIE-based session or have been asked to 
// Make a session cookie
if ( isset($_COOKIE['PHPSESSID']) || defined('COOKIE_SESSION') ) {
	// Do nothing - let the session be in a cookie
} else {
    ini_set('session.use_cookies', '0');
    ini_set('session.use_only_cookies',0);
    ini_set('session.use_trans_sid',1); 
}

if ( ! isset($CFG) ) die("Please configure this product using config.php");
if ( ! isset($CFG->staticroot) ) die('$CFG->staticroot not defined see https://github.com/csev/webauto/issues/2');
if ( ! isset($CFG->bootstrap) ) die('$CFG->bootstrap not defined in config.php');
if ( ! isset($CFG->timezone) ) die('$CFG->timezone not defined in config.php');

// Set this to the temporary folder if not set - dev only 
if ( ! isset($CFG->dataroot) ) {
	$tmp = sys_get_temp_dir();
    if (strlen($tmp) > 1 && substr($tmp, -1) == '/') $tmp = substr($tmp,0,-1);
	$CFG->dataroot = $tmp;
}


error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ALL );
ini_set('display_errors', 1);

date_default_timezone_set($CFG->timezone);

function htmlspec_utf8($string) {
    return htmlspecialchars($string,ENT_QUOTES,$encoding = 'UTF-8');
}

function htmlent_utf8($string) {
    return htmlentities($string,ENT_QUOTES,$encoding = 'UTF-8');
}

function sessionize($url) {
    if ( ini_get('session.use_cookies') != '0' ) return $url;
    $parameter = session_name().'='.session_id();
    if ( strpos($url, $parameter) !== false ) return $url;
    $url = $url . (strpos($url,'?') > 0 ? "&" : "?");
    $url = $url . $parameter;
    return $url;
}

// No trailer
