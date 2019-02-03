<?php
$this->layout('layout/json');

$uid = isset($_POST['uid']) ? intval($_POST['uid']) :null;
$token = isset($_POST['token']) ? trim($_POST['token']) :null;

// POST
$id_lahan = isset($_POST['id_lahan']) ? intval($_POST['id_lahan']) :null;
$id_rumus = isset($_POST['id_rumus']) ? intval($_POST['id_rumus']) :null;
$jumlah = isset($_POST['jumlah']) ? intval($_POST['jumlah']) :null;
$usia = isset($_POST['usia']) ? trim($_POST['usia']) :null;
// $tanam = isset($_POST['tanam']) ? trim($_POST['tanam']) :null;

$ctoken = $this->user()->checkToken($uid, $token);
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    $rumus =  $this->rumus()->get($id_rumus);
    $lahan = $this->lahan()->get($id_lahan);
    $luas = 0;
    $tonase = 0;
    $panen = "";
    $hari = 0;

    $tanam = datePast($usia . " day");
    // $id_lahan, $id_rumus, $jumlah, $usia, $tanam
    $id = $this->komoditas()->add($id_lahan, $id_rumus, $jumlah, $usia, $tanam);
    if ($id)
    {
        switch($lahan->satuan) {
            case 'H':
                $luas = $lahan->luas;
                break;
            case 'T':
                $luas = ($lahan->luas * 14) / 10000;
                break;
            case 'M':
                $luas = $lahan->luas / 10000;
                break;
            default:
                $luas = $lahan->luas;
                break;
        }
        if ($rumus->p_a == 0) {
            $tonase = ($jumlah * $rumus->p_b) / $rumus->p_c;
        }
        else {
            $tonase = ($luas * $rumus->p_b) / $rumus->p_c;
        }
        if ($rumus->panen >= 2) {
            $hari = $rumus->umur - $usia;
            $tonase = $tonase / $rumus->panen;
            for ($i = 0; $i < $rumus->panen; $i++) {
                $tanggal = dateFuture($hari . " day");
                // $this->kalendar()->add($uid, $id, $tanggal, $tonase);
                $hari += $rumus->hari;
            }
        }
        else {
            $panen = $rumus->umur - $usia;
            $tanggal = dateFuture($panen . " day");
            // $this->kalendar()->add($uid, $id, $tanggal, $tonase);
        }
        $data = array_merge($data, array("data" => $this->komoditas()->get($id)));
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