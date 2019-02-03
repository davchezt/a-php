<?php
$this->layout('layout/json');

$uid = isset($_POST['uid']) ? intval($_POST['uid']) : null;
$token = isset($_POST['token']) ? trim($_POST['token']) : null;

$ctoken = $this->user()->checkToken($uid, $token);
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    $data['data'] = array();
    $komoditas = $this->komoditas()->getAll($uid);
    foreach ($komoditas as $k) {
        $kalendar = array();
        $time = array();
        $lahan = $this->lahan()->get($k['id_lahan']);
        $rumus =  $this->rumus()->get($k['id_rumus']);
        $kalendar['panen_tonase'] = 0;
        // $kalendar['lahan'] = $lahan;
        $kalendar['lahan_id'] = $lahan->id;
        $kalendar['lahan_nama'] = $lahan->nama;
        $kalendar['lahan_lokasi'] = $lahan->lokasi;
        $kalendar['lahan_latitude'] = $lahan->latitude;
        $kalendar['lahan_longitude'] = $lahan->longitude;
        $kalendar['lahan_luas'] = $lahan->luas;
        $kalendar['lahan_satuan'] = $lahan->satuan;
        $kalendar['lahan_foto'] = $lahan->foto;
        $kalendar['time'] = $time;
        $kalendar['panen_nama'] = $rumus->nama;
        $kalendar['komoditas_id'] = $k['id'];
        $kalendar['komoditas_usia'] = $k['usia'];
        $kalendar['komoditas_tanam'] = str_replace("-", "/", datePast($k['usia'] . " day"));
        $luas = 0;
        $panen = "";
        $hari = 0;

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
        if ($k['p_a'] == 0) {
            $kalendar['panen_tonase'] = round((($k['jumlah'] * $k['p_b']) / $k['p_c']), 2);
        }
        else {
            $kalendar['panen_tonase'] = ($luas * $k['p_b']) / $k['p_c'];
        }
        if ($k['panen'] >= 2) {
            $hari = $k['umur'] - $k['usia'];
            $kalendar['panen_tonase'] = round(($kalendar['panen_tonase'] / $k['panen']), 2);
            for ($i = 0; $i < $k['panen']; $i++) {
                $kalendar['panen_tanggal'] = str_replace("-", "/", dateFuture($hari . " day"));
                $p = dateFuture($hari . " day");
                $kalendar['ticon'] = 'checkmark';
                $kalendar['time'] = array_merge($kalendar['time'], array("title" => str_replace("-", "/", dateFuture($hari . " day")), "subtitle" => $rumus->nama));
                $hari += $k['hari'];
                // Tambah ke $data
                if (dateDifference(dateFuture($hari . " day"), timeNow()) == 0) {
                    $kalendar['diff'] = dateDifference(dateFuture($hari . " day"), timeNow());
                    array_push($data['data'], $kalendar);
                }
                else {
                    $kalendar['diff'] = dateDifference(dateFuture($hari . " day"), timeNow());
                    // array_push($data['data'], $kalendar);
                }
            }
        }
        else {
            $panen = $k['umur'] - $k['usia'];
            $kalendar['panen_tanggal'] = str_replace("-", "/", dateFuture($panen . " day"));
            $p = dateFuture($panen . " day");
            $kalendar['ticon'] = 'checkmark';
            $kalendar['time'] = array_merge($kalendar['time'], array("title" => str_replace("-", "/", dateFuture($panen . " day")), "subtitle" => $rumus->nama));
            // Tambah ke $data
            if (dateDifference(dateFuture($panen . " day"), timeNow()) == 0) {
                $kalendar['diff'] = dateDifference(dateFuture($panen . " day"), timeNow());
                array_push($data['data'], $kalendar);
            }
            else {
                $kalendar['diff'] = dateDifference(dateFuture($panen . " day"), timeNow());
            }
        }
    }
    $data['data'] = array_orderby($data['data'], 'komoditas_id', SORT_DESC, 'panen_tanggal', SORT_ASC);
}
else {
    http_response_code(401);
    $data = array_merge($data, array("data" => null));
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>