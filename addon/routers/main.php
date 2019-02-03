<?php
$router->match('GET|POST', '/', function() use ($templates) {
    $info = array(
		'config' => R::get('config')
	);
	$templates->addData(
		$info
	);
	echo $templates->render('main');
});