<?php
$this->layout('layout/json');

$uid = isset($_POST['uid']) ? intval($_POST['uid']) :null;
$token = isset($_POST['token']) ? trim($_POST['token']) :null;
$foto = isset($_POST['foto']) ? trim($_POST['foto']) :null;

$cnf = array(
    "baseDir" => __DTA,
    "uploadDir" => 'avatar',
    "imageWidth" => 400,//400,256,128
    "imageHeight" => 400,//400,256,128
    "watermarkImage" => 'copy.png',
    "jpegQuality" => 100
);

$id = $this->user()->id ? $this->user()->id : $uid;
$ctoken = $this->user()->checkToken($uid, $token) ? 1 : $id;
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    $data = array_merge($data, array("data" => null));

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

    $foto = '/data/avatar/crops/' . $newName . '.' . $ext;

    if ($this->user()->ubahFoto($id, $foto)) {
        $data = array_merge($data, array("data" => $this->user()->get($id)));
    }
}
else {
    http_response_code(401);
    $data = array_merge($data, array("data" => null));
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>