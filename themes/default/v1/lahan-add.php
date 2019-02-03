<?php
$this->layout('layout/json');

$uid = isset($_POST['uid']) ? intval($_POST['uid']) :null;
$token = isset($_POST['token']) ? trim($_POST['token']) :null;

// POST
$nama = isset($_POST['nama']) ? trim($_POST['nama']) :null;
$lokasi = isset($_POST['lokasi']) ? trim($_POST['lokasi']) :null;
$latitude = isset($_POST['latitude']) ? trim($_POST['latitude']) :null;
$longitude = isset($_POST['longitude']) ? trim($_POST['longitude']) :null;
$luas = isset($_POST['luas']) ? intval($_POST['luas']) :null;
$satuan = isset($_POST['satuan']) ? trim($_POST['satuan']) :null;
$foto = isset($_POST['foto']) ? trim($_POST['foto']) :null;

$cnf = array(
    "baseDir" => __DTA,
    "uploadDir" => 'lahan',
    "imageWidth" => 400,//400,256,128
    "imageHeight" => 400,//400,256,128
    "watermarkImage" => 'copy.png',
    "jpegQuality" => 100
);

$ctoken = $this->user()->checkToken($uid, $token);
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    // Upload
    $target_path = time() . '.jpg';
    $imageData = $foto;
    $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
    $imageData = str_replace('data:image/jpg;base64,', '', $imageData);
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageData = base64_decode($imageData);

    $destination = $cnf["baseDir"].DIRECTORY_SEPARATOR.$cnf["uploadDir"].DIRECTORY_SEPARATOR."originals".DIRECTORY_SEPARATOR.$target_path;
    file_put_contents($destination, $imageData);

    $ms = new Image($cnf);
    $dest = $cnf["baseDir"].DIRECTORY_SEPARATOR.$cnf["uploadDir"].DIRECTORY_SEPARATOR."crops".DIRECTORY_SEPARATOR.$target_path;
    $img = $ms->saveAsImage($destination, $dest, $cnf['imageWidth'], $cnf['imageHeight'], "crop", true);

    $newName = pathinfo($dest, PATHINFO_FILENAME);
    $ext = pathinfo($dest, PATHINFO_EXTENSION);

    $foto = '/data/lahan/crops/' . $newName . '.' . $ext;
    $id = $this->lahan()->add($uid, $nama, $lokasi, $latitude, $longitude, $luas, $satuan, $foto);
    if ($id)
    {
        $data = array_merge($data, array("data" => $this->lahan()->get($id)));
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