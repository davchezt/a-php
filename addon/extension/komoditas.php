<?php
use Template\Engine;
use Template\Extension\ExtensionInterface;

class Komoditas implements ExtensionInterface {
    
    public function __construct()
    {
    }

    public function register(Engine $templates)
    {
        $templates->registerFunction('komoditas', [$this, 'getObject']);
    }

    public function getObject()
    {
        return $this;
    }

    // get by lahan id
    public function count($id)
    {
        $id = intval($id);
        SQL::open();
        $dbq = SQL::$db->query("SELECT COUNT(*) FROM `komoditas` WHERE `id_lahan` = :id");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        SQL::close();
        $count = $dbq->fetchColumn();

        return $count;
    }

    public function myCount($id)
    {
        $id = intval($id);
        SQL::open();
        $dbq = SQL::$db->query("SELECT COUNT(*) FROM `komoditas` WHERE `id_lahan` IN (SELECT `lahan`.`id` FROM `lahan` WHERE `lahan`.`id_user` = :id)");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        SQL::close();
        $count = $dbq->fetchColumn();

        return $count;
    }

    // get by user id
    public  function getAll($id)
    {  
        $id = intval($id);
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `komoditas`.`id`, `komoditas`.`id_lahan`, `komoditas`.`id_rumus`, `komoditas`.`jumlah`, `komoditas`.`usia`, `komoditas`.`tanam`, `komoditas`.`panen`, `rumus`.`nama`, `rumus`.`panen`, `rumus`.`p_a`, `rumus`.`p_b`, `rumus`.`p_c`, `rumus`.`umur`, `rumus`.`hari`, `lahan`.`nama` AS `lahan`, `lahan`.`luas` AS `luas_lahan`, `lahan`.`satuan` AS `satuan_lahan`, `lahan`.`lokasi` AS `lokasi_lahan` FROM `komoditas` LEFT JOIN `rumus` ON (`rumus`.`id` = `komoditas`.`id_rumus`) LEFT JOIN `lahan` ON (`lahan`.`id` = `komoditas`.`id_lahan`) WHERE `komoditas`.`id_lahan` IN (SELECT `lahan`.`id` FROM `lahan` WHERE `lahan`.`id_user` = :id) ORDER BY `komoditas`.`id`");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $data = $dbo->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    // get by lahan id
    public function getByLahan($id)
    {
        $id = intval($id);
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `komoditas`.`id`, `komoditas`.`id_lahan`, `komoditas`.`jumlah`, `komoditas`.`usia`, `komoditas`.`tanam`, `komoditas`.`panen`, `rumus`.`nama`, `rumus`.`panen`, `rumus`.`p_a`, `rumus`.`p_b`, `rumus`.`p_c`, `rumus`.`umur`, `rumus`.`hari` FROM `komoditas` LEFT JOIN `rumus` ON (`rumus`.`id` = `komoditas`.`id_rumus`) WHERE `komoditas`.`id_lahan` = :id ORDER BY `komoditas`.`id`");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $data = $dbo->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function get($id)
    {
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `komoditas`.* FROM `komoditas` WHERE `id` = :id LIMIT 1");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $data = $dbo->fetch(PDO::FETCH_OBJ);

        return $data ? $data : null;
    }

    public function getDetail($id)
    {
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `komoditas`.`id`, `komoditas`.`id_lahan`, `komoditas`.`id_rumus`, `komoditas`.`jumlah`, `komoditas`.`usia`, `komoditas`.`tanam`, `komoditas`.`panen`, `lahan`.`nama` AS `nama_lahan`, `lahan`.`lokasi` AS `lokasi_lahan`, `rumus`.`nama`, `rumus`.`panen`, `rumus`.`p_a`, `rumus`.`p_b`, `rumus`.`p_c`, `rumus`.`umur`, `rumus`.`hari` FROM `komoditas` LEFT JOIN `lahan` ON (`lahan`.`id` = `komoditas`.`id_lahan`) LEFT JOIN `rumus` ON (`rumus`.`id` = `komoditas`.`id_rumus`) WHERE `komoditas`.`id` = :id LIMIT 1");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $data = $dbo->fetch(PDO::FETCH_OBJ);

        return $data ? $data : null;
    }

    public function edit($id, $id_rumus, $id_lahan, $jumlah, $usia)
    {
        $id = intval($id);
        try {
            SQL::open();
            $anu = SQL::$db->prepare("UPDATE `komoditas` SET `id_lahan` = :id_lahan, `id_rumus` = :id_rumus, `jumlah` = :jumlah, `usia` = :usia WHERE `komoditas`.`id` = :id");
            $anu->bindParam(':id_rumus', $id_rumus, PDO::PARAM_INT);
            $anu->bindParam(':id_lahan', $id_lahan, PDO::PARAM_INT);
            $anu->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
            $anu->bindParam(':usia', $usia, PDO::PARAM_STR, 12);
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();

            return true;
        } catch(PDOException $ex) {
            return false;
        }

        return false;
    }

    public function panen($id)
    {
        $id = intval($id);
        try {
            SQL::open();
            $anu = SQL::$db->prepare("UPDATE `komoditas` SET `panen` = 1 WHERE `komoditas`.`id` = :id");
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
            $anu = SQL::$db->prepare("DELETE FROM `komoditas` WHERE `komoditas`.`id` = :id");
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();

            return true;
        } catch(PDOException $ex) {
            return false;
        }

        return false;
    }

    public function add($id_lahan, $id_rumus, $jumlah, $usia, $tanam)
    {
        // INSERT INTO `komoditas` (`id`, `id_lahan`, `id_rumus`, `jumlah`, `usia`, `tanam`, `panen`) VALUES (NULL, :id_lahan, :id_rumus, :jumlah, :usia, :tanam, '0')
        try {
            SQL::open();
            $anu = SQL::$db->prepare("INSERT INTO `komoditas` (`id`, `id_lahan`, `id_rumus`, `jumlah`, `usia`, `tanam`, `panen`) VALUES (NULL, :id_lahan, :id_rumus, :jumlah, :usia, :tanam, '0')");
            $anu->bindParam(':id_lahan', $id_lahan, PDO::PARAM_INT);
            $anu->bindParam(':id_rumus', $id_rumus, PDO::PARAM_INT);
            $anu->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
            $anu->bindParam(':usia', $usia, PDO::PARAM_INT);
            $anu->bindParam(':tanam', $tanam, PDO::PARAM_STR, 12);

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