<?php
$this->layout('layout/json');

$uid = isset($_POST['uid']) ? trim($_POST['uid']):'';
$token = isset($_POST['token']) ? trim($_POST['token']):'';

$id = $this->user()->id ? $this->user()->id : $uid;
$ctoken = $this->user()->checkToken($uid, $token);
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    $login = $this->user()->tokenLogin($uid, $token);
    $data = array_merge($data, array("data" => "authorized"));
}
else {
    http_response_code(401);
    $data = array_merge($data, array("data" => null));
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>