<?php
/**
 * @author      Raiza Rhamdan (Leonardo DaVchezt) <davchezt@gmail.com>
 * @copyright   Copyright (c), 2014 Raiza Rhamdan
 * @license		MIT public license
 */

defined("__AGRIFARM") or die("<h1>Akses Ditolak!</h1>");

/* Kelas: SQL
 * Kelas bantuan untuk DB Class <db.php>
 */
 class SQL {
    private static $dbc;
    public static $db;
    public static $cnf;
    public static function open()
    {
        self::$cnf = R::get('config');
        self::$dbc = new DB(
            self::$cnf['SQL']['dsn'],
            self::$cnf['SQL']['dbu'],
            self::$cnf['SQL']['dbp']
        );
        self::$dbc->open();
        self::$db = self::$dbc->db();
    }
    public static function close()
    {
        self::$dbc->close();
        self::$db = null;
    }
}