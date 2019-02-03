<?php
$this->layout('layout/json');

$uid = isset($_POST['uid']) ? intval($_POST['uid']) :null;
$token = isset($_POST['token']) ? trim($_POST['token']) :null;

$alamat = isset($_POST['user_lokasi']) ? trim($_POST['user_lokasi']) :null;

$id = $this->user()->id ? $this->user()->id : $uid;
$ctoken = $this->user()->checkToken($uid, $token) ? 1 : $id;
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    $data = array_merge($data, array("data" => null));
    if ($this->user()->ubahLokasi($id, $alamat)) {
        $data = array_merge($data, array("data" => $this->user()->get($id)));
    }
}
else {
    http_response_code(401);
    $data = array_merge($data, array("data" => null));
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>