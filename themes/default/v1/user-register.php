<?php
$this->layout('layout/json');

$nama = isset($_POST['nama']) ? trim($_POST['nama']):'';
$username = isset($_POST['username']) ? trim($_POST['username']):'';
$password = isset($_POST['password']) ? trim($_POST['password']):'';
$gender = isset($_POST['gender']) ? trim($_POST['gender']):'';
$phone = isset($_POST['phone']) ? trim($_POST['phone']):'';
$register = isset($_POST['register']) ? true : false;

$data = get_respon_code(401);
if ($register) {
    http_response_code(200);
    $data = get_respon_code();
    $daftar = $this->user()->daftar($nama, $gender, $username, $password, $phone);
    switch ($daftar)
    {
    case 0:
        $data = array_merge($data, array("data" => "nama pengguna \"{$username}\" sudah digunakan."));
        $data = array_merge($data, array("user_data" => null));
        break;
    case 1:
        $data = array_merge($data, array("data" => "kesalahan tidak diketahui"));
        $data = array_merge($data, array("user_data" => null));
        break;
    case -1:
        $data = array_merge($data, array("data" => "tidak dapat terhubung ke system, silahkan coba lagi nanti"));
        $data = array_merge($data, array("user_data" => null));
        break;
    default:
        $data = array_merge($data, array("data" => "success"));
        $data = array_merge($data, array("user_data" => $this->user()->getUserData()));
        break;
    }
}
else {
    http_response_code(401);
    $data = array_merge($data, array("data" => null));
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>