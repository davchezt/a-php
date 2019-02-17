<?php
use Template\Engine;
use Template\Extension\ExtensionInterface;

class User implements ExtensionInterface {
    public $id;
    public function __construct()
    {
        $uid = isset($_COOKIE['uid']) ? $_COOKIE['uid']:0;
        $this->id = isset($_SESSION['id']) ? $_SESSION['id'] : $uid;
    }

    public function register(Engine $templates)
    {
        $templates->registerFunction('user', [$this, 'getObject']);
    }

    public function getObject()
    {
        return $this;
    }

    public function all()
    {
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `user`.`id`, `user`.`username`, `user`.`type`, `user`.`join_date` , `profile`.`nama`, `profile`.`alamat`, `profile`.`kelamin`,`profile`.`latitude`, `profile`.`longitude`, `profile`.`kontak`, `profile`.`email`, `profile`.`foto`, `alamat`.`lokasi`, `alamat`.`kecamatan`, `alamat`.`kabupaten`, `alamat`.`provinsi`, `alamat`.`negara`, `alamat`.`kodepos`, `alamat`.`kodenegara`, `alamat`.`no` FROM `user` LEFT JOIN `profile` ON (`profile`.`id_user` = `user`.`id`) LEFT JOIN `alamat` ON (`alamat`.`id_user` = `user`.`id`) WHERE `user`.`id` <> :id ORDER BY `id`");
        $dbo->bindValue(':id', $this->id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $petugas = $dbo->fetchAll(PDO::FETCH_ASSOC);

        return $petugas;
    }

    public function getList($offset = 0, $limit = 10)
    {
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `user`.`id`, `user`.`username`, `user`.`type`, `user`.`join_date` , `profile`.`nama`, `profile`.`alamat`, `profile`.`kelamin`,`profile`.`latitude`, `profile`.`longitude`, `profile`.`kontak`, `profile`.`email`, `profile`.`foto` FROM `user` LEFT JOIN `profile` ON (`profile`.`id_user` = `user`.`id`) WHERE `user`.`id` <> :id ORDER BY `id` ASC LIMIT $offset, $limit");
        $dbo->bindValue(':id', $this->id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $petugas = $dbo->fetchAll(PDO::FETCH_ASSOC);

        return $petugas;
    }

    public function getData($type, $offset = 0, $limit = 10, $detail = false) {
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `id` FROM `user` WHERE `type` = :type ORDER BY `id` ASC LIMIT $offset, $limit");
        if ($detail) {
            $dbo = SQL::$db->prepare("SELECT `user`.`id`, `user`.`username`, `user`.`type`, `user`.`join_date` , `profile`.`nama`, `profile`.`alamat`, `profile`.`kelamin`,`profile`.`latitude`, `profile`.`longitude`, `profile`.`kontak`, `profile`.`email`, `profile`.`foto` FROM `user` LEFT JOIN `profile` ON (`profile`.`id_user` = `user`.`id`) WHERE `user`.`type` = :type ORDER BY `id` ASC LIMIT $offset, $limit");
        }
        $dbo->bindParam(':type', $type, PDO::PARAM_STR, 12);
        $dbo->execute();
        SQL::close();
        $petugas = $dbo->fetchAll(PDO::FETCH_ASSOC);

        return $petugas;
    }

    public function get($id)
    {
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `user`.`id`, `user`.`token`, `user`.`type`, `user`.`join_date`, `profile`.`nama`, `profile`.`alamat`, `profile`.`kelamin`,`profile`.`latitude`, `profile`.`longitude`, `profile`.`kontak`, `profile`.`email`, `profile`.`foto` FROM `user` LEFT JOIN `profile` ON (`profile`.`id_user` = `user`.`id`) WHERE `user`.`id` = :id LIMIT 1");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $user = $dbo->fetch(PDO::FETCH_OBJ);
        
        return $user ? $user : null;
    }

    public function getPublic($id)
    {
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `user`.`id`, `user`.`type`, `user`.`join_date`, `profile`.`nama`, `profile`.`alamat`, `profile`.`kelamin`, `profile`.`latitude`, `profile`.`longitude`, `profile`.`kontak`, `profile`.`email`, `profile`.`foto` FROM `user` LEFT JOIN `profile` ON (`profile`.`id_user` = `user`.`id`) WHERE `user`.`id` = :id LIMIT 1");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $user = $dbo->fetch(PDO::FETCH_OBJ);
        
        return $user ? $user : null;
    }

    public function getNama($id)
    {
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `nama` FROM `profile` WHERE `id_user` = :id LIMIT 1");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $nama = $dbo->fetchColumn();
        
        return $nama ? $nama : null;
    }

    public function me()
    {
        return $this->get($this->id);
    }

    public function count()
    {
        SQL::open();
        $dbq = SQL::$db->query("SELECT COUNT(*) FROM `user`");
        SQL::close();
        $count = $dbq->fetchColumn();

        return $count;
    }

    public function aktifTerakhir()
    {
        SQL::open();
        $dbq = SQL::$db->query("SELECT MAX(`join_date`) FROM `user`");
        SQL::close();
        $tanggal = $dbq->fetchColumn();

        return $tanggal;
    }

    public function setActive($id , $verify)
    {
        if (empty($id)) return -1;

        $id = intval($id);
        SQL::open();
        $dbc = SQL::$db->query("SELECT COUNT(*) FROM `user` WHERE `id` = {$id}");
        $count = (int)$dbc->fetchColumn();
        SQL::close();

        if ($count == 0) return 0;

        SQL::open();
        $dbv = SQL::$db->query("SELECT `code` FROM `verify` WHERE `id_user` = {$id}");
        $code = (int)$dbv->fetchColumn();
        SQL::close();

        if ($code != $verify) return 0;

        try {
            SQL::open();
            $anu = SQL::$db->prepare("UPDATE `user` SET `active` = '0' WHERE `user`.`id` = :id");
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();
            $this->regenerateToken();

            return 1;
        } catch(PDOException $ex) {
            return -2;
        }
        
        return -2;
    }

    public function ubahUsername($id, $username, $new_username)
    {
        SQL::open();
        $dbc = SQL::$db->query("SELECT COUNT(*) FROM `user` WHERE `username` = '{$username}'");
        $count = (int)$dbc->fetchColumn();

        if ($count == 0) return 0;
        SQL::close();

        SQL::open();
        $dbc = SQL::$db->query("SELECT COUNT(*) FROM `user` WHERE `username` = '{$new_username}'");
        $count = (int)$dbc->fetchColumn();

        if ($count != 0) return -1;
        SQL::close();

        $id = intval($id);
        try {
            SQL::open();
            $anu = SQL::$db->prepare("UPDATE `user` SET `username` = :username WHERE `user`.`id` = :id");
            $anu->bindParam(':username', $new_username, PDO::PARAM_STR, 12);
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();
            $this->regenerateToken();

            return 1;
        } catch(PDOException $ex) {
            return -2;
        }
        
        return -2;
    }

    public function ubahPassword($id, $old, $new)
    {
        $id = intval($id);

        SQL::open();
        $dbc = SQL::$db->query("SELECT COUNT(*) FROM `user` WHERE `user`.`id` = {$id}");
        $count = (int)$dbc->fetchColumn();
        SQL::close();

        if ($count == 0) return 0;

        SQL::open();
        $dbp = SQL::$db->query("SELECT `password` FROM `user` WHERE `user`.`id` = {$id}");
        $password = $dbp->fetchColumn();
        SQL::close();

        $old_password = md5(R::get('config')['APP']['hash'].".".$old);
        $new_password = md5(R::get('config')['APP']['hash'].".".$new);

        if ($password == $old_password) {
            try {
                SQL::open();
                $anu = SQL::$db->prepare("UPDATE `user` SET `password` = :password WHERE `user`.`id` = :id");
                $anu->bindParam(':password', $new_password, PDO::PARAM_STR, 12);
                $anu->bindParam(':id', $id, PDO::PARAM_INT);
                $anu->execute();
                SQL::close();
                $this->regenerateToken();

                return 1;
            } catch(PDOException $ex) {
                return -2;
            }

            return -1;
        }
        else {
            return 2;
        }
        return -1;
    }

    public function ubahProfile($id, $nama, $kontak, $email, $gender)
    {
        // if (empty($nama) || empty($kontak) || empty($email)) return false;
        $id = intval($id);
        try {
            SQL::open();
            $anu = SQL::$db->prepare("UPDATE `profile` SET `nama` = :nama, `kontak` = :kontak, `email` = :email, `kelamin` = :gender WHERE `profile`.`id_user` = :id");
            $anu->bindParam(':nama', $nama, PDO::PARAM_STR, 12);
            $anu->bindParam(':kontak', $kontak, PDO::PARAM_STR, 12);
            $anu->bindParam(':email', $email, PDO::PARAM_STR, 12);
            $anu->bindParam(':gender', $gender, PDO::PARAM_STR, 12);
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();

            return true;
        } catch(PDOException $ex) {
            return false;
        }

        return false;        
    }

    public function ubahFoto($id, $foto)
    {
        $id = intval($id);
        try {
            SQL::open();
            $anu = SQL::$db->prepare("UPDATE `profile` SET `foto` = :foto WHERE `id_user` IN (SELECT `user`.`id` FROM `user` WHERE `user`.`id` = :id)");
            $anu->bindParam(':foto', $foto, PDO::PARAM_STR, 12);
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();

            return true;
        } catch(PDOException $ex) {
            return false;
        }

        return false;
    }

    public function ubahLokasi($id, $alamat)
    {
        // INSERT INTO `alamat` (`id`, `id_user`, `lokasi`, `kecamatan`, `kabupaten`, `provinsi`, `negara`, `kodepos`, `kodenegara`, `no`) VALUES (NULL, '', '', '', '', '', '', '', '', '')
        if (empty($alamat)) $alamat = null;
        $id = intval($id);
        try {
            SQL::open();
            $anu = SQL::$db->prepare("UPDATE `profile` SET `alamat` = :alamat WHERE `id_user` IN (SELECT `user`.`id` FROM `user` WHERE `user`.`id` = :id)");
            $anu->bindParam(':alamat', $alamat, PDO::PARAM_STR, 12);
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();

            return true;
        } catch(PDOException $ex) {
            return false;
        }

        return false;
    }

    public function ubahLatLng($id, $latitude, $longitude)
    {
        $id = intval($id);
        try {
            SQL::open();
            $anu = SQL::$db->prepare("UPDATE `profile` SET `latitude` = :latitude, `longitude` = :longitude WHERE `id_user` IN (SELECT `user`.`id` FROM `user` WHERE `user`.`id` = :id)");
            $anu->bindValue(':latitude', $latitude, PDO::PARAM_STR);
            $anu->bindValue(':longitude', $longitude, PDO::PARAM_STR);
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();

            return true;
        } catch(PDOException $ex) {
            return false;
        }

        return false;
    }

    public function getUserData()
    {
        $data = array();
        if ($this->id)
        {
            $userData = $this->me();
            $data['user_id'] = $userData->id;
            $data['token'] = $userData->token;
            $data['name'] = $userData->nama;
            $data['gender'] = $userData->kelamin;
            $data['pic'] = $userData->foto;
            $data['type'] = $userData->type;
            $data['latitude'] = $userData->latitude;
            $data['longitude'] = $userData->longitude;
        }

        return $data;
    }

    public function updateTanggal($tanggal)
    {
        $id = intval($this->id);
        try {
            SQL::open();
            $anu = SQL::$db->prepare("UPDATE `user` SET `join_date` = :tanggal WHERE `id` = :id");
            $anu->bindParam(':tanggal', $tanggal, PDO::PARAM_STR, 12);
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();

            return true;
        } catch(PDOException $ex) {
            return false;
        }

        return false;        
    }

    public function getToken()
    {
       return substr(sha1(R::get('config')['APP']['hash'] . "." . time()), 0, 32);
    }

    public function checkToken($id, $token)
    {
        if ($id == null || $token == null) return false;
        
        $id = intval($id);
        SQL::open();
        $dbp = SQL::$db->query("SELECT `token` FROM `user` WHERE `id` = '{$id}'");
        $userToken = $dbp->fetchColumn();
        if ($userToken == $token) {
            if ($this->tokenLogin($id, $token)) {
                return true;
            }
            return false;
        }

        return false;
    }

    public function regenerateToken()
    {
        if ($this->id)
        {
            try {
                $token = $this->getToken();
                SQL::open();
                $anu = SQL::$db->prepare("UPDATE `user` SET `token` = :token WHERE `id` = :id");
                $anu->bindParam(':token', $token, PDO::PARAM_STR, 12);
                $anu->bindParam(':id', $this->id, PDO::PARAM_INT);
                $anu->execute();

                return $token;
            }
            catch(PDOException $ex) {
                return null;
            }
        }
    }

    public function tokenLogin($id, $token)
    {
        if (empty($id) || empty($token)) return -9;
        
        SQL::open();
        $dbc = SQL::$db->query("SELECT COUNT(*) FROM `user` WHERE `id` = '{$id}'");
        $count = (int)$dbc->fetchColumn();
        SQL::close();

        if ($count == 0) return 0;

        SQL::open();
        $dbp = SQL::$db->query("SELECT `token` FROM `user` WHERE `id` = '{$id}'");
        $userToken = $dbp->fetchColumn();

        $userId = SQL::$db->query("SELECT `id` FROM `user` WHERE `id` = '{$id}'");
        $uid = $userId->fetchColumn();
        SQL::close();
        if ($userToken == $token) {
            $_SESSION['id'] = $uid;
            $this->id = $_SESSION['id'];
            setcookie("uid", $this->id, time() + (10 * 365 * 24 * 60 * 60));
            // $this->regenerateToken();

            return 1;
        }
        return -1;
    }

    public function login($username, $password)
    {
        if (empty($username) || empty($password)) return -1;

        SQL::open();
        $dbc = SQL::$db->query("SELECT COUNT(*) FROM `user` WHERE `username` = '{$username}'");
        $count = (int)$dbc->fetchColumn();

        if ($count == 0) return 0;

        $dbp = SQL::$db->query("SELECT `password` FROM `user` WHERE `username` = '{$username}'");
        $password = md5(R::get('config')['APP']['hash'].".".$password);

        $pass = $dbp->fetchColumn();
        $userId = SQL::$db->query("SELECT `id` FROM `user` WHERE `username` = '{$username}'");
        SQL::close();
        if ($pass == $password) {
            $_SESSION['id'] = $userId->fetchColumn();
            $this->id = $_SESSION['id'];
            setcookie("uid", $this->id, time() + (10 * 365 * 24 * 60 * 60));
            $this->regenerateToken();
            
            return 1;
        }
        return -1;
    }

    public function daftar($nama, $kelamin, $username, $password, $phone)
    {
        if (empty($nama) || empty($username) || empty($password)) return -1;
        
        $username = trim($username);
        $password = md5(R::get('config')['APP']['hash'].".".$password);
        $token = $this->getToken();
        $phone = "+62" . ltrim($phone, '0');
        $usephone = false;
    
        SQL::open();
        $dbc = SQL::$db->query("SELECT COUNT(*) FROM `user` WHERE `username` = '{$username}'");
        $count = (int)$dbc->fetchColumn();
        SQL::close();

        if ($count) return 0;

        if (!preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/", $username)){
            $usephone = true;
        }

        try {
            SQL::open();
            $anu = SQL::$db->prepare("INSERT INTO `user` (`id`, `username`, `password`, `token`, `type`, `join_date`, `active`) VALUES(null, :username, :password, :token, '0', NOW(), '0')");
            $anu->bindParam(':username', $username, PDO::PARAM_STR, 12);
            $anu->bindParam(':password', $password, PDO::PARAM_STR, 12);
            $anu->bindParam(':token', $token, PDO::PARAM_STR, 12);
            $anu->execute();
            $id = SQL::$db->lastInsertId();
            SQL::close();

            if ($id)
            {
                // Verivy code
                $code = generateRandomString(5);
                SQL::open();
                $anu = SQL::$db->prepare("INSERT INTO `verify` (`id`, `id_user`, `code`) VALUES (null, :uid, :code)");
                $anu->bindParam(':uid', $id, PDO::PARAM_INT);
                $anu->bindParam(':code', $code, PDO::PARAM_STR, 12);
                $anu->execute();
                SQL::close();
                
                // Profile
                $null = null;
                SQL::open();
                $anu = SQL::$db->prepare("INSERT INTO `profile` (`id`, `id_user`, `nama`, `alamat`, `kelamin`, `latitude`, `longitude`, `kontak`, `email`, `foto`) VALUES (NULL, :uid, :nama, null, :kelamin, null, null, :kontak, :email, null)");
                $anu->bindParam(':uid', $id, PDO::PARAM_INT);
                $anu->bindParam(':kontak', $phone, PDO::PARAM_STR, 12);
                $anu->bindParam(':email', $username, PDO::PARAM_STR, 12);
                $anu->bindParam(':nama', $nama, PDO::PARAM_STR, 12);
                $anu->bindParam(':kelamin', $kelamin, PDO::PARAM_STR, 12);
                $anu->execute();
                SQL::close();

                $_SESSION['id'] = $id;
                $this->id = $_SESSION['id'];
                setcookie("uid", $id, time() + (10 * 365 * 24 * 60 * 60));
            }
            return $id;
        } catch(PDOException $ex) {
            return 1;
        }

        return 1;
    }

    public function logout()
    {
        $_SESSION['id'] = 0;
        unset($_SESSION['id']);

        if (isset($_COOKIE['uid'])) {
            setcookie('uid', null, -1, '/');
            unset($_COOKIE['uid']);
        }
    }

}
?>