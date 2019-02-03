<?php
$this->layout('layout/json');

$requestHeaders = apache_request_headers();
$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
$requestBodys = json_decode(($stream = fopen('php://input', 'r')) !== false ? stream_get_contents($stream) : "{ \"data\": { \"user_id\":\"0\" } }", true);

$token = getToken(['user_id' => ($requestBodys ? $requestBodys['user_id'] : (!empty($_GET) ? trim($_GET['user_id']):'0'))]);
$token = explode('.', $token)[2];
$bearer = explode('.', getBearerToken())[2];
$data = array(
	"method" => $_SERVER['REQUEST_METHOD'],
  // "header" => $requestHeaders,
  "req_token" => $bearer,
  "res_token" => $token,
	"data" => ($requestBodys ? $requestBodys : (!empty($_GET) ? $_GET : $this->user()->id))
);

$data['allowed'] = false;
if ($token == $bearer) {
  $data['allowed'] = true;
}
/*
[HEADER stuff]
Content-Type: application/json
Accept: application/json
User-Agent: Militant Studio Client 1.0
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiMSJ9.6_WA3xB6j192aWBYAub9AmvzJ3m9XhRA7h2t0_STPu4
[BODY stuff]
{
	"_comment": "type: string",
	"user_id": "1",
	"message": "Testing...",
	"_comment": "type: integer",
	"post_id": 720,
	"_comment": "type: null",
	"request_data": null,
	"_comment": "type: object",
	"schema": {
		"method": "POST",
		"callback": false,
		"_comment": "type: array",
		"accept": [
			{ 
				"POST": true 
			}, "PUT", "PATCH", "DELETE" ]
	},
	"_comment": "type: array",
	"cars": [ "Ford", "BMW", "Fiat" ]
}
*/
echo json_encode($data, JSON_PRETTY_PRINT);