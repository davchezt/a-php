<?php
$this->layout('layout/json');

$uid = isset($_POST['uid']) ? intval($_POST['uid']) :null;
$token = isset($_POST['token']) ? trim($_POST['token']) :null;

// POST
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$newpassword = isset($_POST['newpassword']) ? trim($_POST['newpassword']) : '';

$id = $this->user()->id ? $this->user()->id : $uid;
$ctoken = $this->user()->checkToken($uid, $token) ? 1 : $id;
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    $change = $this->user()->ubahPassword($id, $password, $newpassword);
    switch ($change)
    {
    case 0:
        $data = array_merge($data, array("data" => "akses ditolak."));
        $data = array_merge($data, array("user_data" => null));
        break;
    case 1:
        $data = array_merge($data, array("data" => "success"));
        $data = array_merge($data, array("user_data" => $this->user()->getUserData()));
        break;
    case -2:
        $data = array_merge($data, array("data" => "kesalahan pada system, silahkan coba lagi."));
        $data = array_merge($data, array("user_data" => null));
        break;
    case 2:
        $data = array_merge($data, array("data" => "password yang anda masukan salah, silahkan coba lagi."));
        $data = array_merge($data, array("user_data" => null));
        break;
    default:
        $data = array_merge($data, array("data" => "terjadi kesalahan, silahkan coba lagi."));
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