<?php
$this->layout('layout/json');

$uid = isset($_POST['uid']) ? intval($_POST['uid']) : null;
$token = isset($_POST['token']) ? trim($_POST['token']) : null;
$id = isset($_POST['id']) ? intval($_POST['id']) : null;

$ctoken = $this->user()->checkToken($uid, $token);
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    $result = $this->kalendar()->panen($id);
    if ($result) {
        $data = array_merge($data, array("data" => "succes"));
    }
    else {
        $data = array_merge($data, array("data" => "failed"));
    }
}
else {
    http_response_code(401);
    $data = array_merge($data, array("data" => null));
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>