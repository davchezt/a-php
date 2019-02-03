<?php
$this->layout('layout/json');

$uid = isset($_POST['uid']) ? intval($_POST['uid']) :null;
$token = isset($_POST['token']) ? trim($_POST['token']) :null;

// POST
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$newusername = isset($_POST['newusername']) ? trim($_POST['newusername']) : '';

$id = $this->user()->id ? $this->user()->id : $uid;
$ctoken = $this->user()->checkToken($uid, $token) ? 1 : $id;
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    $change = $this->user()->ubahUsername($id, $username, $newusername);

    switch ($change)
    {
    case 0:
        $data = array_merge($data, array("data" => "nama pengguna \"{$username}\" tidak ditemukan."));
        $data = array_merge($data, array("user_data" => null));
        break;
    case 1:
        $data = array_merge($data, array("data" => "success"));
        $data = array_merge($data, array("user_data" => $this->user()->getUserData()));
        break;
    case -1:
        $data = array_merge($data, array("data" => "nama pengguna \"{$newusername}\" sudah terdaftar."));
        $data = array_merge($data, array("user_data" => null));
        break;
    case -2:
        $data = array_merge($data, array("data" => "kesalahan pada system, silahkan coba lagi."));
        $data = array_merge($data, array("user_data" => null));
        break;
    default:
        $data = array_merge($data, array("data" => "password yang anda masukan salah! mohon periksa kembali"));
        $data = array_merge($data, array("user_data" => null));
        break;
    }
}
else {
    http_response_code(401);
    $data = array_merge($data, array("data" => null));
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>