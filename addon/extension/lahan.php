<?php
use Template\Engine;
use Template\Extension\ExtensionInterface;

class Lahan implements ExtensionInterface {
    
    public function __construct()
    {
    }

    public function register(Engine $templates)
    {
        $templates->registerFunction('lahan', [$this, 'getObject']);
    }

    public function getObject()
    {
        return $this;
    }

    public function count($id)
    {
        $id = intval($id);
        SQL::open();
        $dbq = SQL::$db->query("SELECT COUNT(*) FROM `lahan` WHERE `id_user` = :id");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        SQL::close();
        $count = $dbq->fetchColumn();

        return $count;
    }
    
    public  function getAll($id)
    {   
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `l`.`id`, `l`.`nama`, `l`.`lokasi`, `l`.`latitude`, `l`.`longitude`, `l`.`luas`, `l`.`satuan`, `l`.`foto`, COUNT(`k`.`id`) AS `komoditas` FROM `lahan` AS `l` LEFT JOIN `komoditas` AS `k` ON (`k`.`id_lahan` = `l`.`id`) WHERE `l`.`id_user` = :id GROUP BY `l`.`id` ORDER BY `l`.`id`");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $data = $dbo->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function get($id)
    {
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `lahan`.`id`, `lahan`.`nama`, `lahan`.`lokasi`, `lahan`.`latitude`, `lahan`.`longitude`, `lahan`.`luas`, `lahan`.`satuan`, `lahan`.`foto` FROM `lahan` WHERE `lahan`.`id` = :id LIMIT 1");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $data = $dbo->fetch(PDO::FETCH_OBJ);

        return $data ? $data : null;
    }

    public function edit($id, $nama, $lokasi, $latitude, $longitude, $luas, $satuan, $foto = null)
    {
        $id = intval($id);
        try {
            SQL::open();
            $anu = SQL::$db->prepare("UPDATE `lahan` SET `nama` = :nama, `lokasi` = :lokasi, `latitude` = :latitude, `longitude` = :longitude, `luas` = :luas, `satuan` = :satuan WHERE `lahan`.`id` = :id");
            
            $anu->bindParam(':nama', $nama, PDO::PARAM_STR, 12);
            $anu->bindParam(':lokasi', $lokasi, PDO::PARAM_STR, 12);
            $anu->bindParam(':latitude', $latitude, PDO::PARAM_STR, 12);
            $anu->bindParam(':longitude', $longitude, PDO::PARAM_STR, 12);
            $anu->bindParam(':luas', $luas, PDO::PARAM_INT);
            $anu->bindParam(':satuan', $satuan, PDO::PARAM_STR, 12);
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();

            return ($foto) ? $this->editFoto($id, $foto) : true;
        } catch(PDOException $ex) {
            return false;
        }

        return false;
    }

    public function editFoto($id, $foto)
    {
        $id = intval($id);
        try {
            $curfoto = $this->get($id);
            @unlink(__PATH . $curfoto->foto);
            @unlink(__PATH . str_replace("/crops/", "/originals/", $curfoto->foto));

            SQL::open();
            $anu = SQL::$db->prepare("UPDATE `lahan` SET `foto` = :foto WHERE `lahan`.`id` = :id");
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

    public function delete($id)
    {
        $id = intval($id);
        try {
            SQL::open();
            $anu = SQL::$db->prepare("DELETE FROM `lahan` WHERE `lahan`.`id` = :id");
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();

            return true;
        } catch(PDOException $ex) {
            return false;
        }

        return false;
    }

    public function add($id_user, $nama, $lokasi, $latitude, $longitude, $luas, $satuan, $foto)
    {
        try {
            SQL::open();
            $anu = SQL::$db->prepare("INSERT INTO `lahan` (`id`, `id_user`, `nama`, `lokasi`, `latitude`, `longitude`, `luas`, `satuan`, `foto`) VALUES (NULL, :id_user, :nama, :lokasi, :latitude, :longitude, :luas, :satuan, :foto)");
            $anu->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $anu->bindParam(':nama', $nama, PDO::PARAM_STR, 12);
            $anu->bindParam(':lokasi', $lokasi, PDO::PARAM_STR, 12);
            $anu->bindParam(':latitude', $latitude, PDO::PARAM_STR, 12);
            $anu->bindParam(':longitude', $longitude, PDO::PARAM_STR, 12);
            $anu->bindParam(':luas', $luas, PDO::PARAM_INT);
            $anu->bindParam(':satuan', $satuan, PDO::PARAM_STR, 12);
            $anu->bindParam(':foto', $foto, PDO::PARAM_STR, 12);

            $anu->execute();
            $id = SQL::$db->lastInsertId();
            SQL::close();

            if ($id)
            {
                return $id;
            }
            return 0;
        } catch(PDOException $ex) {
            return 0;
        }

        return 0;
    }
}
?>