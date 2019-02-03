<?php
use Template\Engine;
use Template\Extension\ExtensionInterface;

class Kalendar implements ExtensionInterface {
    
    public function __construct()
    {
    }

    public function register(Engine $templates)
    {
        $templates->registerFunction('kalendar', [$this, 'getObject']);
    }

    public function getObject()
    {
        return $this;
    }

    public function count($id)
    {
        $id = intval($id);
        SQL::open();
        $dbq = SQL::$db->query("SELECT COUNT(*) FROM `kalendar` WHERE `id_user` = {$id}");
        SQL::close();
        $count = $dbq->fetchColumn();

        return $count;
    }

    public function getAll($id)
    {
        if ($this->count($id) == 0) {
            return null;
        }
        SQL::open();
        $dbo = SQL::$db->prepare(
            "SELECT `j`.`tanggal`, `j`.`dipanen`, `j`.`tonase`,
            `k`.`jumlah` AS `jumlah_komoditas`, `k`.`usia` AS `usia_komoditas`, `k`.`tanam` AS `tanam_komoditas`,
            `r`.`nama` AS `nama_komoditas`,
            `l`.`nama` AS `nama_lahan`, `l`.`foto` AS `foto_lahan`, `l`.`luas` AS `luas_lahan`, `l`.`satuan` AS `satuan_lahan`, `l`.`lokasi` AS `lokasi_lahan`, `l`.`latitude` AS `lahan_latitude`, `l`.`longitude` AS `lahan_longitude`
            FROM `kalendar` AS `j`
            LEFT JOIN `komoditas` AS `k` ON (`k`.`id` = `j`.`id_komoditas`)
            LEFT JOIN `rumus` AS `r` ON (`r`.`id` = `k`.`id_rumus`)
            LEFT JOIN `lahan` AS `l` ON (`l`.`id` = `k`.`id_lahan`)
            WHERE `j`.`id_user` = :id GROUP BY `j`.`id` ORDER BY `l`.`id`"
        );
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $data = $dbo->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function get($id)
    {
        SQL::open();
        $dbo = SQL::$db->prepare(
            "SELECT `j`.`tanggal`, `j`.`dipanen`, `j`.`tonase`,
            `k`.`jumlah` AS `jumlah_komoditas`, `k`.`usia` AS `usia_komoditas`, `k`.`tanam` AS `tanam_komoditas`,
            `r`.`nama` AS `nama_komoditas`,
            `l`.`nama` AS `nama_lahan`, `l`.`foto` AS `foto_lahan`, `l`.`luas` AS `luas_lahan`, `l`.`satuan` AS `satuan_lahan`, `l`.`lokasi` AS `lokasi_lahan`, `l`.`latitude` AS `lahan_latitude`, `l`.`longitude` AS `lahan_longitude`
            FROM `kalendar` AS `j`
            LEFT JOIN `komoditas` AS `k` ON (`k`.`id` = `j`.`id_komoditas`)
            LEFT JOIN `rumus` AS `r` ON (`r`.`id` = `k`.`id_rumus`)
            LEFT JOIN `lahan` AS `l` ON (`l`.`id` = `k`.`id_lahan`)
            WHERE `j`.`id` = :id GROUP BY `j`.`id` ORDER BY `l`.`id`"
        );
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $data = $dbo->fetch(PDO::FETCH_OBJ);

        return $data ? $data : null;
    }

    public function add($id_user, $id_komoditas, $tanggal, $tonase)
    {
        try {
            SQL::open();
            $anu = SQL::$db->prepare("INSERT INTO `kalendar` (`id`, `id_user`, `id_komoditas`, `tanggal`, `tonase`, `dipanen`) VALUES (NULL, :id_user, :id_komoditas, :tanggal, :tonase, '0')");
            $anu->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $anu->bindParam(':id_komoditas', $id_komoditas, PDO::PARAM_INT);
            $anu->bindParam(':tanggal', $tanggal, PDO::PARAM_STR, 12);
            $anu->bindParam(':tonase', $tonase, PDO::PARAM_STR, 12);
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
            $anu = SQL::$db->prepare("UPDATE `kalendar` SET `dipanen` = '1' WHERE `kalendar`.`id` = :id");
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();

            return true;
        } catch(PDOException $ex) {
            return false;
        }

        return false;
    }
}