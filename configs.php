<?php
// $cfg = parse_ini_file(__PATH . "/config/configs.ini", true);
/*
return $configs = array(
    'SQL' => array(
        'dsn' => "mysql:host=localhost;dbname=agritafa_agrifarm",
        'dbu' => "agritafa_admin",
        'dbp' => "4Bahagia4"
    ),
    'APP' => array(
        'hash' => 'DaVchezt',
        'url'  => 'https://agritama.farm/api'
    )
);
*/
return $configs = array(
    'SQL' => array(
    'dsn' => "mysql:host=localhost;dbname=agrifarm",
        'dbu' => "root",
        'dbp' => ""
    ),
    'APP' => array(
        'hash' => 'DaVchezt',
        'url'  => 'http://192.168.0.108'
    )
);