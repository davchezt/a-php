<?php
$this->layout('layout/json');

$logout = isset($_POST['logout']) ? $_POST['logout']: false;
$data = get_respon_code(401);

if ($logout) {
    http_response_code(200);
    $data = get_respon_code();
    $this->user()->logout();
    $data = array_merge($data, array("data" => "success"));
    $data = array_merge($data, array("user_data" => null));
}
else {
    http_response_code(401);
    $data = array_merge($data, array("data" => null));
}
echo json_encode($data, JSON_PRETTY_PRINT);