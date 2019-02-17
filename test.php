<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');
}
// if (!isset($_POST['name'])) exit;
header('Content-type: application/json');


if( !function_exists('apache_request_headers') ) {
function apache_request_headers() {
  $arh = array();
  $rx_http = '/\AHTTP_/';
  foreach($_SERVER as $key => $val) {
    if( preg_match($rx_http, $key) ) {
      $arh_key = preg_replace($rx_http, '', $key);
      $rx_matches = array();
      // do some nasty string manipulations to restore the original letter case
      // this should work in most cases
      $rx_matches = explode('_', $arh_key);
      if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
        foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
        $arh_key = implode('-', $rx_matches);
      }
      $arh[$arh_key] = $val;
    }
  }
  return( $arh );
}
}

$req = apache_request_headers();
$requestHeaders = array_combine(array_map('ucwords', array_keys($req)), array_values($req));
$token = explode(' ', $requestHeaders['Authorization'])[1];

//build the headers
$headers = ['alg' => 'HS256', 'typ' => 'JWT'];
$headers_encoded = base64url_encode(json_encode($headers));

//build the payload
$data = json_decode(($stream = fopen('php://input', 'r')) !== false ? stream_get_contents($stream) : "{ \"data\": { \"user_id\":\"0\" } }", true);

$uid = isset($data['user_id']) ? trim($data['user_id']):'1';
$payload = ['user_id' => $uid];
$payload_encoded = base64url_encode(json_encode($payload));

//build the signature
$key = 'DaVchezt';
$signature = hash_hmac('SHA256', $headers_encoded . '.' . $payload_encoded, $key, true);
$signature_encoded = base64url_encode($signature);

//build and return the token
$tokens = $headers_encoded . '.' . $payload_encoded . '.' . $signature_encoded;
$jwt_output = array(
	'res_token' => $tokens,
	'req_token' => getBearerToken(),
	'req_data' => $data,
	'req_headers' => $requestHeaders,
	'req_method' => getenv('REQUEST_METHOD')
);

echo json_encode($jwt_output, JSON_PRETTY_PRINT);

function base64url_encode($data) { 
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
}

/** 
 * Get header Authorization
 * */
function getAuthorizationHeader(){
	$headers = null;
	if (isset($_SERVER['Authorization'])) {
		$headers = trim($_SERVER["Authorization"]);
	}
	else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
		$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	} elseif (function_exists('apache_request_headers')) {
		$requestHeaders = apache_request_headers();
		// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		//print_r($requestHeaders);
		if (isset($requestHeaders['Authorization'])) {
			$headers = trim($requestHeaders['Authorization']);
		}
	}
	return $headers;
}
/**
 * get access token from header
 * */
function getBearerToken() {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}
















