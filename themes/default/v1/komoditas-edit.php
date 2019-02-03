<?php
$this->layout('layout/json');

$uid = isset($_POST['uid']) ? intval($_POST['uid']) :null;
$token = isset($_POST['token']) ? trim($_POST['token']) :null;

// POST
$rumus = isset($_POST['rumus']) ? intval($_POST['rumus']) :null;
$lahan = isset($_POST['lahan']) ? trim($_POST['lahan']) :null;
$jumlah = isset($_POST['jumlah']) ? trim($_POST['jumlah']) :null;
$usia = isset($_POST['usia']) ? trim($_POST['usia']) :null;

$ctoken = $this->user()->checkToken($uid, $token);
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    if ($this->komoditas()->edit($id, $rumus, $lahan, $jumlah, $usia))
    {
        // $data = array_merge($data, array("data" => $this->komoditas()->get($id)));
        $data = array_merge($data, array("data" => "Data telah di perbaharui"));
    }
    else {
        $data = array_merge($data, array("data" => null));
    }
    
}
else {
    http_response_code(401);
    $data = array_merge($data, array("data" => null));
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>