<?php
/**
 * This file contains constants and shortcut functions that are commonly used.
 * Please only include functions are most widely used because this file
 * is included for every request. Functions are less often used are better
 * encapsulated as static methods in helper classes that are loaded on demand.
 */

/**
 * This is the shortcut to DIRECTORY_SEPARATOR
 */
defined('DS') or define('DS',DIRECTORY_SEPARATOR);

function yiiCorrectShutdown()
{
    $error = error_get_last();
	$errorsMask = E_ERROR; // all the errors on which a special action should be taken to save logs
    if ($error['type'] & $errorsMask)
	{
		Yii::log("A Fatal php error occured: ".print_r($error, true), "error");
		Yii::app()->end();
		//the following line will work as well, and may be better if one wants to only execute logs-flushing on fatal errors
		//Yii::app()->log->processLogs(null);
    }
}
register_shutdown_function('yiiCorrectShutdown');

// Default charcter set
define('DEFAULT_CHAR_SET', 'UTF-8');

/**
 *Download content as text
 */
function downloadAs($name, $content, $type='text') {
	$types = array(
					'text' => 'text/plain',
					'pdf' => 'application/pdf',
					'word' => 'application/msword',
					'xml' => 'text/xml',
					);

	$exts = array(
					'text' => 'txt',
					'pdf' => 'pdf',
					'word' => 'doc',
					'xml' => 'xml',
					);	

	header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header('Pragma: no-cache');
	header("Content-Type: ".$types[ $type ]."");
	header("Content-Disposition: attachment; filename=\"".$name . '.' . $exts[ $type ] ."\";");
    header("Content-Length: ".mb_strlen($content));
	echo $content;
	exit;
}

/**
 * Return list of files recursive
 */
function glob_recursive($pattern, $flags = 0){
    $files = glob($pattern, $flags);
    
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
    }
    
    return $files;
}

/**
 * Remove directory and all files
 */
function recursiveDirRemove($dir) {
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file)) {
            recursiveDirRemove($file);
        } else {
            @unlink($file);
    	}
	}
    @rmdir($dir);
}

/**
 * Echo a json string or a normal string
 * @param mixed $mixed
 * @retrun void
 */
function echoJson($mixed) {
	if(is_array($mixed) || is_object($mixed)) {
		echo CJSON::encode($mixed);
	} else {
		echo $mixed;
	}
	Yii::app()->end();
}

/**
 * Echo a json string with the 500 error code
 * @param mixed $value
 */
function echoJsonError($value) {
	header('HTTP/1.1 500 Internal Server Error');
	echoJson($value);
}


/**
 * Echo a string with the 500 error code
 * @param mixed $value
 */
function echoError($value) {
	header('HTTP/1.1 500 Internal Server Error');
	echo $value;
	Yii::app()->end();
}

/**
 * Echo message in the command line
 */
function echoCli($message, $exit=false) {
	echo $message . "\n";
	if($exit) {
		Yii::app()->end();
	}
}

/**
 * Strip bad text
 *
 */
function stripHtmlTags( $text )  { 
	// Replace values
	$text = str_replace( "<!--"		, "&#60;&#33;--"  , $text );
	$text = str_replace( "-->"			, "--&#62;"       , $text );
	$text = str_ireplace( "<script"	, "&#60;script"   , $text );
	$text = str_ireplace( "<head"	, "&#60;head"   , $text );
	$text = str_ireplace( "<body"	, "&#60;body"   , $text );
	$text = str_ireplace( "<style"	, "&#60;style"   , $text );
	$text = str_ireplace( "<object"	, "&#60;object"   , $text );
	$text = str_ireplace( "<embed"	, "&#60;embed"   , $text );
	$text = str_ireplace( "<applet"	, "&#60;applet"   , $text );
	$text = str_ireplace( "<noframes"	, "&#60;noframes"   , $text );
	$text = str_ireplace( "<noscript"	, "&#60;noscript"   , $text );
	$text = str_ireplace( "<noembed"	, "&#60;noembed"   , $text );
	$text = str_replace( "\r"			, ""              , $text );
	
	$text = str_ireplace( "</script"	, "&#60;/script"   , $text );
	$text = str_ireplace( "</head"	, "&#60;/head"   , $text );
	$text = str_ireplace( "</body"	, "&#60;/body"   , $text );
	$text = str_ireplace( "</style"	, "&#60;/style"   , $text );
	$text = str_ireplace( "</object"	, "&#60;/object"   , $text );
	$text = str_ireplace( "</embed"	, "&#60;/embed"   , $text );
	$text = str_ireplace( "</applet"	, "&#60;/applet"   , $text );
	$text = str_ireplace( "</noframes"	, "&#60;/noframes"   , $text );
	$text = str_ireplace( "</noscript"	, "&#60;/noscript"   , $text );
	$text = str_ireplace( "</noembed"	, "&#60;/noembed"   , $text );
	
    $text = preg_replace( 
        array( 
          // Remove invisible content 
            '@<head[^>]*?>.*?</head>@siu', 
			'@<body[^>]*?>.*?</body>@siu', 
            '@<style[^>]*?>.*?</style>@siu', 
            '@<script[^>]*?.*?</script>@siu', 
            '@<object[^>]*?.*?</object>@siu', 
            '@<embed[^>]*?.*?</embed>@siu', 
            '@<applet[^>]*?.*?</applet>@siu', 
            '@<noframes[^>]*?.*?</noframes>@siu', 
            '@<noscript[^>]*?.*?</noscript>@siu', 
            '@<noembed[^>]*?.*?</noembed>@siu', 
        ), 
        array( 
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',"$0", "$0", "$0", "$0", "$0", "$0","$0", "$0",), $text );
    return $text;
}

function sh($t) {
	return stripHtmlTags($t);
}

/**
 * Purify HTML
 */
function ph($text) {
	$purifier = new CHtmlPurifier();
	$purifier->options = array(
	    'URI.AllowedSchemes' => 'http, https',
	    'HTML.Allowed' => 'p,b,i,u,s,strong,strike,big,small,a[href],ul,ol,li,blockquote,h3,h4,h5,h6,br,hr,code,pre,sub,sup,span,div'
	);
	return $purifier->purify(stripHtmlTags($text));
}

/**
 * Simple function that checks if a string is serialized - is not bullet proof
 * but will work in most cases
 * @param string $string
 * @return boolean
 */
function isSerialized($string) {
	if(is_array(safeUnSerialize($string)) || is_object(safeUnSerialize($string))) {
		return true;
	}
	return false;
}

/**
 * Safe way to serialize an object or an array
 * @param array|object $array
 * @return string
 */
function safeSerialize($array) {
	return base64_encode(@serialize($array));
}

/**
 * Safe way to unserialize a string
 * @param string $string
 * @return array|object
 */
function safeUnSerialize($string) {
	return @unserialize(base64_decode($string));
}

/**
 * Log an admin log
 * @param string $messsage
 * @return boolean
 */
function alog($message) {
	if(!trim($message)) {
		return false;
	}
	
	$userId = Yii::app()->user->id ? Yii::app()->user->id : 0;
	$note = trim($message);
	$ipAddress = Yii::app()->request ? Yii::app()->request->getUserHostAddress() : '';
	$controller = Yii::app()->getController() ? Yii::app()->getController()->id : '';
	$action = ($controller && Yii::app()->getController()->getAction()) ? Yii::app()->getController()->getAction()->id : '';
	
	// Add to db
	$model = new AdminLog;
	$model->user_id = $userId;
	$model->note = $note;
	$model->ip_address = $ipAddress;
	$model->controller = $controller;
	$model->action = $action;
	return $model->save();
}

/**
 * Create url
 * @param string
 * @return string
 */
function createUrl($url) {
	return Yii::app()->urlManager->createUrl($url);
}

/**
 * Check if there is a user flash set
 * @param string $key
 * @return boolean
 */
function hasFlash($key) {
	return Yii::app()->user->hasFlash($key);
}

/**
 * Get the user flash set
 * @param string $key
 * @return string
 */
function getFlash($key) {
	return Yii::app()->user->getFlash($key);
}

/**
 * Set user flash
 * @param string $key
 * @param string $value
 * @return string
 */
function setFlash($key, $value) {
	return Yii::app()->user->setFlash($key, $value);
}

/**
 * Return the CWebUser object
 * @return object
 */
function getUser() {
	return Yii::app()->user;
}

/**
 * Return app param
 * @return mixed
 */
function getParam($key, $default=null) {
	return Yii::app()->settings->get($key, $default);
}

/**
 * Return request param
 * @param string $key
 * @param string $default
 * @return mixed
 */
function getRParam($key, $default=null) {
	return Yii::app()->request->getParam($key, $default);
}

/**
 * Return request param
 * @param string $key
 * @param string $default
 * @return mixed
 */
function getPostParam($key, $default=null) {
	return Yii::app()->request->getPost($key, $default);
}

/**
 * Return request object
 * @return object
 */
function request() {
	return Yii::app()->request;
}

function isAjax() {
	return request()->isAjaxRequest;
}

/**
 * Begin profile timer
 * @return object
 */
function bp($name, $category='profile.admin') {
	Yii::beginProfile($name, $category);
}

/**
 * End profile timer
 * @return object
 */
function ep($name, $category='profile.admin') {
	Yii::endProfile($name, $category);
}

/**
 * Return the url referrer
 * @param string $default
 * @return string
 */
function getReferrer($default='/') {
	return Yii::app()->request->getUrlReferrer() ? Yii::app()->request->getUrlReferrer() : $default;
}

/**
 * Register script file
 * @param string $url
 * @param string $position
 * @return void
 */
function JSFile($url,$position=CClientScript::POS_END) {
	Yii::app()->clientScript->registerScriptFile($url, $position);
}

/**
 * Register script code
 * @param string $id
 * @param string $script
 * @param string $position
 * @return void
 */
function JSCode($id, $script, $position=CClientScript::POS_END) {
	Yii::app()->clientScript->registerScript($id, $script, $position);
}

/**
 * Regsiter css file
 * @param string $url
 * @param string $media
 * @return void
 */
function CSSFile($url,$media='') {
	Yii::app()->clientScript->registerCssFile($url, $media);
}

/**
 * Publish file
 * @param string $url
 * @return string
 */
function publish($location) {
	return Yii::app()->assetManager->publish($location);
}

/**
 * Check if user has access to $key
 * @param string $key
 * @return boolean
 */
function checkAccess($key) {
	return Yii::app()->user->checkAccess($key);
}

/**
 * Get application base path
 * @return string
 */
function getBasePath() {
	return Yii::getPathOfAlias('webroot');
}

/**
 * Get application base url
 * @return string
 */
function getBaseUrl() {
	return Yii::app()->baseUrl;
}

/**
 * Get uploads base path
 * @return string
 */
function getUploadsPath() {
	$path = getBasePath();
	if(getParam('uploads_dir')) {
		$path .= '/'.getParam('uploads_dir');
	}
	return $path;
}

/**
 * Get uploads base url
 * @return string
 */
function getUploadsUrl() {
	$url = getBaseUrl();
	if(getParam('uploads_dir')) {
		$url .= '/'.getParam('uploads_dir');
	}
	return $url;
}

/**
 * Return a formatted date and time
 * @param int $timestamp
 * @param string $dateWidth
 * @param string $timeWidth
 * @return string
 */
function dateTime($timestamp, $dateWidth='short', $timeWidth='short') {
	return Yii::app()->dateFormatter->formatDateTime($timestamp, $dateWidth, $timeWidth);
}

/**
 * Return a formatted date only
 * @param int $timestamp
 * @param string $dateWidth
 * @param string $timeWidth
 * @return string
 */
function dateOnly($timestamp, $dateWidth='short', $timeWidth=null) {
	return Yii::app()->dateFormatter->formatDateTime($timestamp, $dateWidth, $timeWidth);
}

/**
 * Calculate the time since ago
 * @param int
 * @param int
 * @return string
 */
function timeSince($iTime0, $iTime1 = 0)
{
	// If not time specified then return null
	if(!$iTime0) {
		return null;
	}
    if ($iTime1 == 0) { 
		$iTime1 = time(); 
	}
    $iTimeElapsed = $iTime1 - $iTime0;

    if ($iTimeElapsed < (60)) {
        return "Less than a minute ago";
    } else if ($iTimeElapsed < (60*60)) {
        $iNum = intval($iTimeElapsed / 60); $sUnit = "minute";
    } else if ($iTimeElapsed < (10*60*60)) {
        $iNum = intval($iTimeElapsed / (60*60)); $sUnit = "hour";
    } else {
        return dateTime($iTime0);
    }

    return $iNum . " " . $sUnit . (($iNum != 1) ? "s" : "") . " ago";
}

/**
 * Return a formatted number
 * @param int $int
 * @return string
 */
function numberFormat($int) {
	return Yii::app()->format->number($int);
}

/**
 * Return a formatted bytes size
 * @param int $bytes
 * @return string
 */
function formatBytes($bytes) {
   if ($bytes < 1024) return intval($bytes).' B';
   elseif ($bytes < 1048576) return round($bytes / 1024, 2).' KB';
   elseif ($bytes < 1073741824) return round($bytes / 1048576, 2).' MB';
   elseif ($bytes < 1099511627776) return round($bytes / 1073741824, 2).' GB';
   else return round($bytes / 1099511627776, 2).' TB';
}

/**
 * Check if user has access to $key
 * if not throw an exception
 * @param string $key
 * @return exception
 */
function checkAccessThrowException($key) {
	if(!checkAccess($key)) {
		// Log Message
		alog(at("User tried accessing a restricted area. Has no permission: {name}", array('{name}' => $key)));
		
		throw new CHttpException(403, Yii::t('error', 'Sorry, You don\'t have the required permissions to enter or perform this action.'));
	}
}

/**
 * Copy a file, or recursively copy a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function copyr($source, $dest, $overWrite=true) {
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }
     
    // Simple copy for a file
    if (is_file($source)) {
        if(file_exists($dest)) {
			if($overWrite) {
				return copy($source, $dest);
			} else {
				return true;
			}
		} else {
			return copy($source, $dest);
		}
    }
 
    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }
 
    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }
 
        // Deep copy directories
        copyr("$source/$entry", "$dest/$entry", $overWrite);
    }
 
    // Clean up
    $dir->close();
    return true;
}

/**
 * Get user ip, figure out if he uses proxy , make sure not pick up internal ip
 * 
 */
function getUserIP()
{
    $alt_ip = $_SERVER['REMOTE_ADDR'];

    if (isset($_SERVER['HTTP_CLIENT_IP']))
    {
        $alt_ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches))
    {
        // make sure we dont pick up an internal IP defined by RFC1918
        foreach ($matches[0] AS $ip)
        {
            if (!preg_match("#^(10|172\.16|192\.168)\.#", $ip))
            {
                $alt_ip = $ip;
                break;
            }
        }
    }
    else if (isset($_SERVER['HTTP_FROM']))
    {
        $alt_ip = $_SERVER['HTTP_FROM'];
    }

    return $alt_ip;
}

function app() {
	return Yii::app();
}

function user() {
	return app()->user;
}

function fok($message) {
	return user()->setFlash('ok', $message);
}

function ferror($message) {
	return user()->setFlash('error', $message);
}

function finfo($message) {
	return user()->setFlash('info', $message);
}

function fwarning($message) {
	return user()->setFlash('warning', $message);
}

function themeUrl($file=null) {
	return getThemeBaseUrl() . '/' .  ($file!== null ? $file : '');
}

function getThemeBaseUrl() {
	return app()->theme->baseUrl . '/www';
}

function cs() {
	return app()->clientScript;
}

function t($message, $category='global', $params=array()) {
	return Yii::t($category, $message, $params);
}

function at($message, $params=array()) {
	return t($message, 'admin', $params);
}

/**
 * Return array of supported cache options
 */
function getSupprotedCacheOptions() {
	$cacheOptions = array(
		'CFileCache' => array('title' => 'File Cache', 'visible' => true),
		'CDbCache' => array('title' => 'Database Cache', 'visible' => true),
		'CApcCache' => array('title' => 'APC Cache', 'visible' => extension_loaded('apc')),
		'CEAcceleratorCache' => array('title' => 'EAccelerator Cache', 'visible' => function_exists('eaccelerator_get')),
		'CMemCache' => array('title' => 'Mem Cache', 'visible' => @class_exists('Memcache')),
		'CWinCache' => array('title' => 'Win Cache', 'visible' => extension_loaded('wincache') && ini_get('wincache.ucenabled')),
		'CXCache' => array('title' => 'XCache', 'visible' => function_exists('xcache_isset')),
		'CZendDataCache' => array('title' => 'Zend Data Cache', 'visible' => function_exists('zend_shm_cache_store')),
	);
	
	return $cacheOptions;
}

/**
 * Return list of time zones
 */
function getTimeZones() {
	return array(
		"Pacific/Midway" => "(GMT-11:00) Midway Island, Samoa",
		"America/Adak" => "(GMT-10:00) Hawaii-Aleutian",
		"Etc/GMT+10" => "(GMT-10:00) Hawaii",
		"Pacific/Marquesas" => "(GMT-09:30) Marquesas Islands",
		"Pacific/Gambier" => "(GMT-09:00) Gambier Islands",
		"America/Anchorage" => "(GMT-09:00) Alaska",
		"America/Ensenada" => "(GMT-08:00) Tijuana, Baja California",
		"Etc/GMT+8" => "(GMT-08:00) Pitcairn Islands",
		"America/Los_Angeles" => "(GMT-08:00) Pacific Time (US & Canada)",
		"America/Denver" => "(GMT-07:00) Mountain Time (US & Canada)",
		"America/Chihuahua" => "(GMT-07:00) Chihuahua, La Paz, Mazatlan",
		"America/Dawson_Creek" => "(GMT-07:00) Arizona",
		"America/Belize" => "(GMT-06:00) Saskatchewan, Central America",
		"America/Cancun" => "(GMT-06:00) Guadalajara, Mexico City, Monterrey",
		"Chile/EasterIsland" => "(GMT-06:00) Easter Island",
		"America/Chicago" => "(GMT-06:00) Central Time (US & Canada)",
		"America/New_York" => "(GMT-05:00) Eastern Time (US & Canada)",
		"America/Havana" => "(GMT-05:00) Cuba",
		"America/Bogota" => "(GMT-05:00) Bogota, Lima, Quito, Rio Branco",
		"America/Caracas" => "(GMT-04:30) Caracas",
		"America/Santiago" => "(GMT-04:00) Santiago",
		"America/La_Paz" => "(GMT-04:00) La Paz",
		"Atlantic/Stanley" => "(GMT-04:00) Faukland Islands",
		"America/Campo_Grande" => "(GMT-04:00) Brazil",
		"America/Goose_Bay" => "(GMT-04:00) Atlantic Time (Goose Bay)",
		"America/Glace_Bay" => "(GMT-04:00) Atlantic Time (Canada)",
		"America/St_Johns" => "(GMT-03:30) Newfoundland",
		"America/Araguaina" => "(GMT-03:00) UTC-3",
		"America/Montevideo" => "(GMT-03:00) Montevideo",
		"America/Miquelon" => "(GMT-03:00) Miquelon, St. Pierre",
		"America/Godthab" => "(GMT-03:00) Greenland",
		"America/Argentina/Buenos_Aires" => "(GMT-03:00) Buenos Aires",
		"America/Sao_Paulo" => "(GMT-03:00) Brasilia",
		"America/Noronha" => "(GMT-02:00) Mid-Atlantic",
		"Atlantic/Cape_Verde" => "(GMT-01:00) Cape Verde Is.",
		"Atlantic/Azores" => "(GMT-01:00) Azores",
		"Europe/Belfast" => "(GMT) Greenwich Mean Time : Belfast",
		"Europe/Dublin" => "(GMT) Greenwich Mean Time : Dublin",
		"Europe/Lisbon" => "(GMT) Greenwich Mean Time : Lisbon",
		"Europe/London" => "(GMT) Greenwich Mean Time : London",
		"Africa/Abidjan" => "(GMT) Monrovia, Reykjavik",
		"Europe/Amsterdam" => "(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
		"Europe/Belgrade" => "(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague",
		"Europe/Brussels" => "(GMT+01:00) Brussels, Copenhagen, Madrid, Paris",
		"Africa/Algiers" => "(GMT+01:00) West Central Africa",
		"Africa/Windhoek" => "(GMT+01:00) Windhoek",
		"Asia/Beirut" => "(GMT+02:00) Beirut",
		"Africa/Cairo" => "(GMT+02:00) Cairo",
		"Asia/Gaza" => "(GMT+02:00) Gaza",
		"Africa/Blantyre" => "(GMT+02:00) Harare, Pretoria",
		"Asia/Jerusalem" => "(GMT+02:00) Jerusalem",
		"Europe/Minsk" => "(GMT+02:00) Minsk",
		"Asia/Damascus" => "(GMT+02:00) Syria",
		"Europe/Moscow" => "(GMT+03:00) Moscow, St. Petersburg, Volgograd",
		"Africa/Addis_Ababa" => "(GMT+03:00) Nairobi",
		"Asia/Tehran" => "(GMT+03:30) Tehran",
		"Asia/Dubai" => "(GMT+04:00) Abu Dhabi, Muscat",
		"Asia/Yerevan" => "(GMT+04:00) Yerevan",
		"Asia/Kabul" => "(GMT+04:30) Kabul",
		"Asia/Yekaterinburg" => "(GMT+05:00) Ekaterinburg",
		"Asia/Tashkent" => "(GMT+05:00) Tashkent",
		"Asia/Kolkata" => "(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi",
		"Asia/Katmandu" => "(GMT+05:45) Kathmandu",
		"Asia/Dhaka" => "(GMT+06:00) Astana, Dhaka",
		"Asia/Novosibirsk" => "(GMT+06:00) Novosibirsk",
		"Asia/Rangoon" => "(GMT+06:30) Yangon (Rangoon)",
		"Asia/Bangkok" => "(GMT+07:00) Bangkok, Hanoi, Jakarta",
		"Asia/Krasnoyarsk" => "(GMT+07:00) Krasnoyarsk",
		"Asia/Hong_Kong" => "(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi",
		"Asia/Irkutsk" => "(GMT+08:00) Irkutsk, Ulaan Bataar",
		"Australia/Perth" => "(GMT+08:00) Perth",
		"Australia/Eucla" => "(GMT+08:45) Eucla",
		"Asia/Tokyo" => "(GMT+09:00) Osaka, Sapporo, Tokyo",
		"Asia/Seoul" => "(GMT+09:00) Seoul",
		"Asia/Yakutsk" => "(GMT+09:00) Yakutsk",
		"Australia/Adelaide" => "(GMT+09:30) Adelaide",
		"Australia/Darwin" => "(GMT+09:30) Darwin",
		"Australia/Brisbane" => "(GMT+10:00) Brisbane",
		"Australia/Hobart" => "(GMT+10:00) Hobart",
		"Asia/Vladivostok" => "(GMT+10:00) Vladivostok",
		"Australia/Lord_Howe" => "(GMT+10:30) Lord Howe Island",
		"Etc/GMT-11" => "(GMT+11:00) Solomon Is., New Caledonia",
		"Asia/Magadan" => "(GMT+11:00) Magadan",
		"Pacific/Norfolk" => "(GMT+11:30) Norfolk Island",
		"Asia/Anadyr" => "(GMT+12:00) Anadyr, Kamchatka",
		"Pacific/Auckland" => "(GMT+12:00) Auckland, Wellington",
		"Etc/GMT-12" => "(GMT+12:00) Fiji, Kamchatka, Marshall Is.",
		"Pacific/Chatham" => "(GMT+12:45) Chatham Islands",
		"Pacific/Tongatapu" => "(GMT+13:00) Nuku'alofa",
		"Pacific/Kiritimati" => "(GMT+14:00) Kiritimati",
	);
}

/**
 * Make an SEO title for use in the URL
 *
 * @access	public
 * @param	string		Raw SEO title or text
 * @return	string		Cleaned up SEO title
 */
function makeAlias( $text )
{
	if ( ! $text )
	{
		return '';
	}
	
	$text = str_replace( array( '`', ' ', '+', '.', '?', '_', '%' ), '-', $text );
	
	/* Strip all HTML tags first */
	$text = strip_tags($text);
		
	/* Preserve %data */
	$text = preg_replace('#%([a-fA-F0-9][a-fA-F0-9])#', '-xx-$1-xx-', $text);
	$text = str_replace( array( '%', '`' ), '', $text);
	$text = preg_replace('#-xx-([a-fA-F0-9][a-fA-F0-9])-xx-#', '%$1', $text);

	/* Convert accented chars */
	$text = convertAccents($text);
	
	/* Convert it */
	if ( isUTF8( $text )  )
	{
		if ( function_exists('mb_strtolower') )
		{
			$text = mb_strtolower($text, 'UTF-8');
		}

		$text = utf8Encode( $text, 500 );
	}

	/* Finish off */
	$text = strtolower($text);
	
	if ( strtolower( Yii::app()->charset ) == 'utf-8' )
	{
		$text = preg_replace( '#&.+?;#'        , '', $text );
		$text = preg_replace( '#[^%a-z0-9 _-]#', '', $text );
	}
	else
	{
		/* Remove &#xx; and &#xxx; but keep &#xxxx; */
		$text = preg_replace( '/&#(\d){2,3};/', '', $text );
		$text = preg_replace( '#[^%&\#;a-z0-9 _-]#', '', $text );
		$text = str_replace( array( '&quot;', '&amp;'), '', $text );
	}
	
	$text = str_replace( array( '`', ' ', '+', '.', '?', '_' ), '-', $text );
	$text = preg_replace( "#-{2,}#", '-', $text );
	$text = trim($text, '-');
	
	return ( $text ) ? $text : '-';
}

/**
 * Seems like UTF-8?
 * hmdker at gmail dot com {@link php.net/utf8_encode}
 *
 * @access	public
 * @param	string		Raw text
 * @return	boolean
 */
function isUTF8($str) {
    $c=0; $b=0;
    $bits=0;
    $len=strlen($str);
    for($i=0; $i<$len; $i++)
    {
        $c=ord($str[$i]);

        if($c > 128)
        {
            if(($c >= 254)) return false;
            elseif($c >= 252) $bits=6;
            elseif($c >= 248) $bits=5;
            elseif($c >= 240) $bits=4;
            elseif($c >= 224) $bits=3;
            elseif($c >= 192) $bits=2;
            else return false;

            if(($i+$bits) > $len) return false;

            while( $bits > 1 )
            {
                $i++;
                $b = ord($str[$i]);
                if($b < 128 || $b > 191) return false;
                $bits--;
            }
        }
    }

    return true;
}

/**
 * Converts accented characters into their plain alphabetic counterparts
 *
 * @access	public
 * @param	string		Raw text
 * @return	string		Cleaned text
 */
function convertAccents($string)
{
	if ( ! preg_match('/[\x80-\xff]/', $string) )
	{
		return $string;
	}

	if ( isUTF8( $string) )
	{
		$_chr = array(
						/* Latin-1 Supplement */
						chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
						chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
						chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
						chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
						chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
						chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
						chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
						chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
						chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
						chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
						chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
						chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
						chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
						chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
						chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
						chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
						chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
						chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
						chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
						chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
						chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
						chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
						chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
						chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
						chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
						chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
						chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
						chr(195).chr(191) => 'y',
						/* Latin Extended-A */
						chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
						chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
						chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
						chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
						chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
						chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
						chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
						chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
						chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
						chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
						chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
						chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
						chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
						chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
						chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
						chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
						chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
						chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
						chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
						chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
						chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
						chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
						chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
						chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
						chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
						chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
						chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
						chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
						chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
						chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
						chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
						chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
						chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
						chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
						chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
						chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
						chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
						chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
						chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
						chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
						chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
						chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
						chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
						chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
						chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
						chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
						chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
						chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
						chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
						chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
						chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
						chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
						chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
						chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
						chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
						chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
						chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
						chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
						chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
						chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
						chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
						chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
						chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
						chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
						/* Euro Sign */
						chr(226).chr(130).chr(172) => 'E',
						/* GBP (Pound) Sign */
						chr(194).chr(163) => '' );

		$string = strtr($string, $_chr);
	}
	else
	{
		$_chr      = array();
		$_dblChars = array();
		
		/* We assume ISO-8859-1 if not UTF-8 */
		$_chr['in'] =   chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
						.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
						.chr(195).chr(199).chr(200).chr(201).chr(202)
						.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
						.chr(211).chr(212).chr(213).chr(217).chr(218)
						.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
						.chr(231).chr(232).chr(233).chr(234).chr(235)
						.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
						.chr(244).chr(245).chr(249).chr(250).chr(251)
						.chr(252).chr(253).chr(255).chr(191).chr(182).chr(179).chr(166)
						.chr(230).chr(198).chr(175).chr(172).chr(188)
						.chr(163).chr(161).chr(177);

		$_chr['out'] = "EfSZszYcYuAAAACEEEEIIIINOOOOUUUUYaaaaceeeeiiiinoooouuuuyyzslScCZZzLAa";

		$string           = strtr( $string, $_chr['in'], $_chr['out'] );
		$_dblChars['in']  = array( chr(140), chr(156), chr(196), chr(197), chr(198), chr(208), chr(214), chr(216), chr(222), chr(223), chr(228), chr(229), chr(230), chr(240), chr(246), chr(248), chr(254));
		$_dblChars['out'] = array('Oe', 'oe', 'Ae', 'Aa', 'Ae', 'DH', 'Oe', 'Oe', 'TH', 'ss', 'ae', 'aa', 'ae', 'dh', 'oe', 'oe', 'th');
		$string           = str_replace($_dblChars['in'], $_dblChars['out'], $string);
	}
			
	return $string;
}

/**
 * Manually utf8 encode to a specific length
 * Based on notes found at php.net
 *
 * @access	public
 * @param	string		Raw text
 * @param	int			Length
 * @return	string
 */
function utf8Encode( $string, $len=0 )
{
	$_unicode       = '';
	$_values        = array();
	$_nOctets       = 1;
	$_unicodeLength = 0;
	$stringLength   = strlen( $string );

	for ( $i = 0 ; $i < $stringLength ; $i++ )
	{
		$value = ord( $string[ $i ] );

		if ( $value < 128 )
		{
			if ( $len && ( $_unicodeLength >= $len ) )
			{
				break;
			}

			$_unicode .= chr($value);
			$_unicodeLength++;
		}
		else
		{
			if ( count( $_values ) == 0 )
			{
				$_nOctets = ( $value < 224 ) ? 2 : 3;
			}

			$_values[] = $value;

			if ( $len && ( $_unicodeLength + ($_nOctets * 3) ) > $len )
			{
				break;
			}

			if ( count( $_values ) == $_nOctets )
			{
				if ( $_nOctets == 3 )
				{
					$_unicode .= '%' . dechex($_values[0]) . '%' . dechex($_values[1]) . '%' . dechex($_values[2]);
					$_unicodeLength += 9;
				}
				else
				{
					$_unicode .= '%' . dechex($_values[0]) . '%' . dechex($_values[1]);
					$_unicodeLength += 6;
				}

				$_values  = array();
				$_nOctets = 1;
			}
		}
	}

	return $_unicode;
}