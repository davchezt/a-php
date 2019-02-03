<?php
use Template\Engine;
use Template\Extension\ExtensionInterface;

class Rumus implements ExtensionInterface {
    
    public function __construct()
    {
    }

    public function register(Engine $templates)
    {
        $templates->registerFunction('rumus', [$this, 'getObject']);
    }

    public function getObject()
    {
        return $this;
    }

    public function count()
    {
        SQL::open();
        $dbq = SQL::$db->query("SELECT COUNT(*) FROM `rumus`");
        SQL::close();
        $count = $dbq->fetchColumn();

        return $count;
    }

    public function getList()
    {
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `rumus`.`id`, `rumus`.`nama` FROM `rumus` ORDER BY `nama`");
        $dbo->execute();
        SQL::close();
        $data = $dbo->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function get($id)
    {
        SQL::open();
        $dbo = SQL::$db->prepare("SELECT `rumus`.* FROM `rumus` WHERE `rumus`.`id` = :id LIMIT 1");
        $dbo->bindValue(':id', $id, PDO::PARAM_INT);
        $dbo->execute();
        SQL::close();
        $data = $dbo->fetch(PDO::FETCH_OBJ);

        return $data ? $data : null;
    }

    public function edit($id, $nama, $panen, $p_a, $p_b, $p_c, $umur, $hari)
    {
        $id = intval($id);
        try {
            SQL::open();
            $anu = SQL::$db->prepare("UPDATE `rumus` SET `nama` = :nama, `panen` = :panen, `p_a` = :p_a, `p_b` = :p_b, `p_c` = :p_c, `umur` = :umur, `hari` = :hari WHERE `rumus`.`id` = :id");
            $anu->bindParam(':nama', $nama, PDO::PARAM_STR, 12);
            $anu->bindParam(':panen', $panen, PDO::PARAM_INT);
            $anu->bindParam(':p_a', $p_a, PDO::PARAM_INT);
            $anu->bindParam(':p_b', $p_b, PDO::PARAM_STR, 12);
            $anu->bindParam(':p_c', $p_c, PDO::PARAM_INT);
            $anu->bindParam(':umur', $umur, PDO::PARAM_INT);
            $anu->bindParam(':hari', $hari, PDO::PARAM_INT);
            $anu->bindParam(':id', $id, PDO::PARAM_INT);
            $anu->execute();
            SQL::close();

            return true;
        } catch(PDOException $ex) {
            return false;
        }

        return false;
    }

    public function add($nama, $panen, $p_a, $p_b, $p_c, $umur, $hari)
    {
        $id = intval($id);
        try {
            SQL::open();
            $anu = SQL::$db->prepare("INSERT INTO `rumus` (`id`, `nama`, `panen`, `p_a`, `p_b`, `p_c`, `umur`, `hari`) VALUES (NULL, :nama, :panen, :p_a, :p_b, :p_c, :umur, :hari)");
            $anu->bindParam(':nama', $nama, PDO::PARAM_STR, 12);
            $anu->bindParam(':panen', $panen, PDO::PARAM_INT);
            $anu->bindParam(':p_a', $p_a, PDO::PARAM_INT);
            $anu->bindParam(':p_b', $p_b, PDO::PARAM_STR, 12);
            $anu->bindParam(':p_c', $p_c, PDO::PARAM_INT);
            $anu->bindParam(':umur', $umur, PDO::PARAM_INT);
            $anu->bindParam(':hari', $hari, PDO::PARAM_INT);
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