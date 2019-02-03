<?php
////////// POST /////////
$router->match('POST', '/user/login', function() use ($templates) {
    $info = array(
        'status' 	=> '200',
        'response' 	=> 'ok'
	);
	$templates->addData($info);
	echo $templates->render('login');
});

$router->match('POST', '/user/token', function() use ($templates) {
    $info = array(
        'status' 	=> '200',
        'response' 	=> 'ok'
	);
	$templates->addData($info);
	echo $templates->render('token');
});

$router->match('POST', '/user/logout', function() use ($templates) {
    $info = array(
        'status' 	=> '200',
        'response' 	=> 'ok'
	);
	$templates->addData($info);
	echo $templates->render('logout');
});

$router->match('POST', '/user/register', function() use ($templates) {
    $info = array(
        'status' 	=> '200',
        'response' 	=> 'ok'
	);
	$templates->addData($info);
	echo $templates->render('register');
});

////////// GET //////////
$router->match('GET', '/user', function() use ($templates) {
    $info = array(
        'status' 	=> '200',
        'response' 	=> 'ok'
	);
	$templates->addData($info);
	echo $templates->render('user');
});

$router->match('GET', '/user/get:me', function() use ($templates) {
    $info = array(
		'status' 	=> '200',
        'response' 	=> 'ok',
		'user_id' 	=> (isset($_SESSION['id']) ? $_SESSION['id']:null)
	);
	$templates->addData($info);
	echo $templates->render('user');
});

$router->match('GET', '/user/get:(:num)', function($id) use ($templates) {
    $info = array(
		'status' 	=> '200',
        'response' 	=> 'ok',
		'user_id' 	=> $id
	);
	$templates->addData($info);
	echo $templates->render('user');
});

$router->match('GET', '/user/start:(:num)&limit:(:num)', function($offset, $limit) use ($templates) {
    $info = array(
		'status' 	=> '200',
        'response' 	=> 'ok',
		'offset' 	=> $offset,
		'limit' 	=> $limit
	);
	$templates->addData($info);
	echo $templates->render('user');
});