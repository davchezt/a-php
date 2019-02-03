<?php
$this->layout('layout/json');

$uid = isset($_POST['uid']) ? intval($_POST['uid']) : null;
$token = isset($_POST['token']) ? trim($_POST['token']) : null;
$usia = isset($_POST['usia']) ? trim($_POST['usia']) : '0';
if (empty($usia)) $usia = 0;

$ctoken = $this->user()->checkToken($uid, $token);
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    $panen = dateFuture($usia . " day");
    $data = array_merge($data, array("data" => $panen));
}
else {
    http_response_code(401);
    $data = array_merge($data, array("data" => null));
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>