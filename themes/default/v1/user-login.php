<?php
$this->layout('layout/json');

$username = isset($_POST['username']) ? trim($_POST['username']):'';
$password = isset($_POST['password']) ? trim($_POST['password']):'';
$login = isset($_POST['login']) ? true : false;

$data = get_respon_code(401);
if ($login) {
    http_response_code(200);
    $data = get_respon_code();
    $login = $this->user()->login($username, $password);
    switch ($login)
    {
    case 0:
        $data = array_merge($data, array("data" => "nama pengguna \"{$username}\" tidak ditemukan."));
        $data = array_merge($data, array("user_data" => null));
        break;
    case 1:
        $data = array_merge($data, array("data" => "success"));
        $data = array_merge($data, array("user_data" => $this->user()->getUserData()));
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