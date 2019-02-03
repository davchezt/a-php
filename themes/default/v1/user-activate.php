<?php
$this->layout('layout/json');

$uid    = isset($_POST['uid'])      ? intval($_POST['uid']) :null;
$token  = isset($_POST['token'])    ? trim($_POST['token']) :null;
$verify  = isset($_POST['verify'])    ? trim($_POST['verify']) :null;

$ctoken = $this->user()->checkToken($uid, $token);
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    $login = $this->user()->setActive($uid, $verify);
    switch ($login)
    {
    case 0:
        $data = array_merge($data, array("data" => "kode sudah tidak valid."));
        $data = array_merge($data, array("user_data" => null));
        break;
    case 1:
        $data = array_merge($data, array("data" => "success"));
        $data = array_merge($data, array("user_data" => $this->user()->getUserData()));
        break;
    default:
        $data = array_merge($data, array("data" => "kesalahan, silahkan hubungi administrator"));
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