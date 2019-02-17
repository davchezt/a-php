<?php
defined("__AGRIFARM") or die("<h1>Akses Ditolak!</h1>");

// Fungsi untuk merubah huruf menjadi Kapital
function HurufBesar($text)
{
    return strtoupper($text);
}
function Perkalian($berapa)
{
	for ($i = 1; $i <= 10; $i++)
	{
		echo $i, " X ", $berapa, " = ", $i * $berapa, "<br />";
	}
}
function timeNow($unix = false, $time = false/*, $patern = ','*/)
{
	if ($unix and $time) return time() + (+7 * 60 * 60);
	
	$userTimezone = new DateTimeZone('Asia/Jakarta');
	$now = new DateTime("now", $userTimezone);

	if ($unix) return $now->format('U');
	return /*sprintf(*/$now->format("Y-m-d H:i:s")/*, $patern)*/;
}
function relative_time($date) {
	if(is_numeric($date)) $date = '@' . $date;

	$user_timezone = new DateTimeZone('Asia/Jakarta');
	$date = new DateTime($date, $user_timezone);

	// get current date in user timezone
	$now = new DateTime('now', $user_timezone);

	$elapsed = $now->format('U') - $date->format('U');

	if($elapsed <= 1) {
		return 'Baru saja';
	}

	$times = array(
		31104000 => 'tahun',
		2592000 => 'bulan',
		604800 => 'minggu',
		86400 => 'hari',
		3600 => 'jam',
		60 => 'menit',
		1 => 'detik'
	);

	foreach($times as $seconds => $title) {
		$rounded = $elapsed / $seconds;

		if($rounded > 1) {
			$rounded = round($rounded);
			return $rounded . ' ' . $title . ' yang lalu';
		}
	}
}
function relative_time_short($date) {
	if(is_numeric($date)) $date = '@' . $date;

	$user_timezone = new DateTimeZone('Asia/Jakarta');
	$date = new DateTime($date, $user_timezone);

	// get current date in user timezone
	$now = new DateTime('now', $user_timezone);

	$elapsed = $now->format('U') - $date->format('U');

	if($elapsed <= 1) {
		return 'Baru saja';
	}

	$times = array(
		31104000 => 'm',
		2592000 => 'm',
		604800 => 'm',
		86400 => 'h',
		3600 => 'j',
		60 => 'm',
		1 => 'd'
	);

	foreach($times as $seconds => $title) {
		$rounded = $elapsed / $seconds;

		if($rounded > 1) {
			$rounded = round($rounded);
			return $rounded . $title;
		}
	}
}
function dateFuture($modify, $format = "Y-m-d")
{
	$user_timezone = new DateTimeZone('Asia/Jakarta');
	$date = new DateTime('now', $user_timezone);
	$date->modify("+" . $modify); // "+15 day"
	return $date->format($format);
}
function datePast($modify, $format = "Y-m-d")
{
	$user_timezone = new DateTimeZone('Asia/Jakarta');
	$date = new DateTime('now', $user_timezone);
	$date->modify("-" . $modify); // "+15 day"
	return $date->format($format);
}
function listingDir($path)
{
    if (empty($path)) $path = ".";

    $fileList = $directoryList = array();
    $ignoreList = array(".", "..", ".htaccess");
    if (is_dir($path)) {
        $directoryHandle  = opendir($path);
        while (false !== ($file = readdir($directoryHandle)))
        {
            if (in_array($file, $ignoreList)) continue;
            if (is_dir($path . "/" . $file)) {
                $directoryList["dirs"][] = array(
                    "file" => $file,
                    "location" => $path,
                    "type" => "dir"
                );
            }
            else {
                $fileList["files"][] = array(
                    "file" => $file,
                    "location" => $path,
                    "type" => "file"
                );
            }
        }
        closedir($directoryHandle);
    }
    natcasesort($directoryList);
    natcasesort($fileList);
    $finalList = array_merge($directoryList, $fileList);

    return $finalList;
}
if (!function_exists('http_response_code')) {
	function http_response_code($code = NULL) {

		if ($code !== NULL) {

			switch ($code) {
				case 100: $text = 'Continue'; break;
				case 101: $text = 'Switching Protocols'; break;
				case 200: $text = 'OK'; break;
				case 201: $text = 'Created'; break;
				case 202: $text = 'Accepted'; break;
				case 203: $text = 'Non-Authoritative Information'; break;
				case 204: $text = 'No Content'; break;
				case 205: $text = 'Reset Content'; break;
				case 206: $text = 'Partial Content'; break;
				case 300: $text = 'Multiple Choices'; break;
				case 301: $text = 'Moved Permanently'; break;
				case 302: $text = 'Moved Temporarily'; break;
				case 303: $text = 'See Other'; break;
				case 304: $text = 'Not Modified'; break;
				case 305: $text = 'Use Proxy'; break;
				case 400: $text = 'Bad Request'; break;
				case 401: $text = 'Unauthorized'; break;
				case 402: $text = 'Payment Required'; break;
				case 403: $text = 'Forbidden'; break;
				case 404: $text = 'Not Found'; break;
				case 405: $text = 'Method Not Allowed'; break;
				case 406: $text = 'Not Acceptable'; break;
				case 407: $text = 'Proxy Authentication Required'; break;
				case 408: $text = 'Request Time-out'; break;
				case 409: $text = 'Conflict'; break;
				case 410: $text = 'Gone'; break;
				case 411: $text = 'Length Required'; break;
				case 412: $text = 'Precondition Failed'; break;
				case 413: $text = 'Request Entity Too Large'; break;
				case 414: $text = 'Request-URI Too Large'; break;
				case 415: $text = 'Unsupported Media Type'; break;
				case 500: $text = 'Internal Server Error'; break;
				case 501: $text = 'Not Implemented'; break;
				case 502: $text = 'Bad Gateway'; break;
				case 503: $text = 'Service Unavailable'; break;
				case 504: $text = 'Gateway Time-out'; break;
				case 505: $text = 'HTTP Version not supported'; break;
				default:
					exit('Unknown http status code "' . htmlentities($code) . '"');
				break;
			}

			$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

			header($protocol . ' ' . $code . ' ' . $text);

			$GLOBALS['http_response_code'] = $code;

		} else {

			$code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);

		}

		return $code;

	}
}

function get_respon_code($code = null)
{
	switch ($code) {
		case 100: $text = 'Continue'; break;
		case 101: $text = 'Switching Protocols'; break;
		case 200: $text = 'OK'; break;
		case 201: $text = 'Created'; break;
		case 202: $text = 'Accepted'; break;
		case 203: $text = 'Non-Authoritative Information'; break;
		case 204: $text = 'No Content'; break;
		case 205: $text = 'Reset Content'; break;
		case 206: $text = 'Partial Content'; break;
		case 300: $text = 'Multiple Choices'; break;
		case 301: $text = 'Moved Permanently'; break;
		case 302: $text = 'Moved Temporarily'; break;
		case 303: $text = 'See Other'; break;
		case 304: $text = 'Not Modified'; break;
		case 305: $text = 'Use Proxy'; break;
		case 400: $text = 'Bad Request'; break;
		case 401: $text = 'Unauthorized'; break;
		case 402: $text = 'Payment Required'; break;
		case 403: $text = 'Forbidden'; break;
		case 404: $text = 'Not Found'; break;
		case 405: $text = 'Method Not Allowed'; break;
		case 406: $text = 'Not Acceptable'; break;
		case 407: $text = 'Proxy Authentication Required'; break;
		case 408: $text = 'Request Time-out'; break;
		case 409: $text = 'Conflict'; break;
		case 410: $text = 'Gone'; break;
		case 411: $text = 'Length Required'; break;
		case 412: $text = 'Precondition Failed'; break;
		case 413: $text = 'Request Entity Too Large'; break;
		case 414: $text = 'Request-URI Too Large'; break;
		case 415: $text = 'Unsupported Media Type'; break;
		case 500: $text = 'Internal Server Error'; break;
		case 501: $text = 'Not Implemented'; break;
		case 502: $text = 'Bad Gateway'; break;
		case 503: $text = 'Service Unavailable'; break;
		case 504: $text = 'Gateway Time-out'; break;
		case 505: $text = 'HTTP Version not supported'; break;
		default:
			$text = 'OK';
		break;
	}

	return array(
		"status" => $code ? $code:200,
		"response" => $text
	);
}

function generateRandomString($length = 10) {
    //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

//////////////////////////////////////////////////////////////////////
//PARA: Date Should In YYYY-MM-DD Format
//RESULT FORMAT:
// '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
// '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
// '%m Month %d Day'                                            =>  3 Month 14 Day
// '%d Day %h Hours'                                            =>  14 Day 11 Hours
// '%d Day'                                                        =>  14 Days
// '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
// '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
// '%h Hours                                                    =>  11 Hours
// '%a Days                                                        =>  468 Days
//////////////////////////////////////////////////////////////////////
function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
    $interval = date_diff($datetime1, $datetime2);
    
    return $interval->format($differenceFormat);
}