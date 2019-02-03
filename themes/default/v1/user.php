<?php
$this->layout('layout/json');

$uid    = isset($_POST['uid'])      ? intval($_POST['uid']) :null;
$token  = isset($_POST['token'])    ? trim($_POST['token']) :null;

$ctoken = $this->user()->checkToken($uid, $token) ? 1 : $this->user()->id;
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    if (isset($offset) && isset($limit)) {
        $data = array_merge($data, array("data" => $this->user()->list($offset, $limit)));
    }
    else {
        $data = array_merge($data, array("data" => $this->user()->all()));
    }
}
else {
    http_response_code(401);
    $data = array_merge($data, array("data" => null));
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>