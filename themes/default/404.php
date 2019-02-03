<?php
$this->layout('layout/json');

$data = get_respon_code(404);
$data = array_merge($data, array("data" => null));
http_response_code(404);

echo json_encode($data, JSON_PRETTY_PRINT);