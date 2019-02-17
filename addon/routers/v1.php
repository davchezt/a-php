<?php
$router->match('GET|POST', '/v1', function() use ($templates) {
    $info = array(
		'config' => R::get('config')
	);
	$templates->addData(
		$info
	);
	echo $templates->render('main');
});
// API: Lahan
// @param id_lahan
$router->match('POST|GET', '/v1/lahan', function() use ($templates) {
	echo $templates->render('/v1/lahan');
});
// @param lahan.id
$router->match('POST|GET', '/v1/lahan/(:num)', function($id) use ($templates) {
    $info = array(
        'id' 	=> $id
	);
	$templates->addData($info);
	echo $templates->render('/v1/lahan-id');
});
$router->match('POST|GET', '/v1/lahan/edit/(:num)', function($id) use ($templates) {
    $info = array(
        'id' 	=> $id
	);
	$templates->addData($info);
	echo $templates->render('/v1/lahan-edit');
});
$router->match('POST|GET', '/v1/lahan/foto/(:num)', function($id) use ($templates) {
    $info = array(
        'id' 	=> $id
	);
	$templates->addData($info);
	echo $templates->render('/v1/lahan-foto');
});
$router->match('POST|GET', '/v1/lahan/delete/(:num)', function($id) use ($templates) {
    $info = array(
        'id' 	=> $id
	);
	$templates->addData($info);
	echo $templates->render('/v1/lahan-delete');
});
$router->match('POST|GET', '/v1/lahan/add', function() use ($templates) {
    echo $templates->render('/v1/lahan-add');
});

/*******************************************************************************
 *******************************************************************************
 *******************************************************************************/
// API: Komoditas
$router->match('POST|GET', '/v1/komoditas', function() use ($templates) {
    echo $templates->render('/v1/komoditas');
});
$router->match('POST|GET', '/v1/komoditas/lahan/(:num)', function($id) use ($templates) {
	$info = array(
        'id' 	=> $id
	);
	$templates->addData($info);
    echo $templates->render('/v1/komoditas-lahan');
});
$router->match('POST|GET', '/v1/komoditas/(:num)', function($id) use ($templates) {
    $info = array(
        'id' 	=> $id
	);
	$templates->addData($info);
	echo $templates->render('/v1/komoditas-id');
});
$router->match('POST|GET', '/v1/komoditas/detail/(:num)', function($id) use ($templates) {
    $info = array(
        'id' 	=> $id
	);
	$templates->addData($info);
	echo $templates->render('/v1/komoditas-detail');
});
$router->match('POST|GET', '/v1/komoditas/edit/(:num)', function($id) use ($templates) {
    $info = array(
        'id' 	=> $id
	);
	$templates->addData($info);
	echo $templates->render('/v1/komoditas-edit');
});
$router->match('POST|GET', '/v1/komoditas/delete/(:num)', function($id) use ($templates) {
    $info = array(
        'id' 	=> $id
	);
	$templates->addData($info);
	echo $templates->render('/v1/komoditas-delete');
});
$router->match('POST|GET', '/v1/komoditas/add', function() use ($templates) {
    echo $templates->render('/v1/komoditas-add');
});

/*******************************************************************************
 *******************************************************************************
 *******************************************************************************/
// API: Rumus
$router->match('POST|GET', '/v1/rumus', function() use ($templates) {
	echo $templates->render('/v1/rumus');
});
$router->match('POST|GET', '/v1/rumus/(:num)', function($id) use ($templates) {
	$info = array(
        'id' 	=> $id
	);
	$templates->addData($info);
	echo $templates->render('/v1/rumus-id');
});


// API: User
/*******************************************************************************
 *******************************************************************************
 *******************************************************************************/
////////// POST /////////
$router->match('POST|GET', '/v1/user/activate', function() use ($templates) {
    echo $templates->render('v1/user-activate');
});
$router->match('POST|GET', '/v1/user/login', function() use ($templates) {
    echo $templates->render('v1/user-login');
});
$router->match('POST|GET', '/v1/user/login-adm', function() use ($templates) {
    echo $templates->render('v1/admin-login');
});
$router->match('POST|GET', '/v1/user/token', function() use ($templates) {
    echo $templates->render('v1/user-token');
});
//// Daftar berdasarkan
$router->match('POST|GET', '/v1/user/agronomis', function() use ($templates) {
    echo $templates->render('v1/user-agronomis');
});
$router->match('POST|GET', '/v1/user/agronomis/(:num)/(:num)', function($offset, $limit) use ($templates) {
	$info = array(
		'offset' 	=> $offset,
		'limit' 	=> $limit
	);
	$templates->addData($info);
    echo $templates->render('v1/user-agronomis');
});

$router->match('POST|GET', '/v1/user/petani', function() use ($templates) {
    echo $templates->render('v1/user-petani');
});
$router->match('POST|GET', '/v1/user/petani/(:num)/(:num)', function($offset, $limit) use ($templates) {
	$info = array(
		'offset' 	=> $offset,
		'limit' 	=> $limit
	);
	$templates->addData($info);
    echo $templates->render('v1/user-petani');
});

$router->match('POST|GET', '/v1/user/bandar', function() use ($templates) {
    echo $templates->render('v1/user-bandar');
});
$router->match('POST|GET', '/v1/user/bandar/(:num)/(:num)', function($offset, $limit) use ($templates) {
	$info = array(
		'offset' 	=> $offset,
		'limit' 	=> $limit
	);
	$templates->addData($info);
    echo $templates->render('v1/user-bandar');
});
/*** TAK PERLU */
$router->match('POST|GET', '/v1/user/logout', function() use ($templates) {
    $templates->addData($info);
	echo $templates->render('v1/user-logout');
});
$router->match('POST|GET', '/v1/user/register', function() use ($templates) {
    echo $templates->render('v1/user-register');
});
$router->match('POST|GET', '/v1/user', function() use ($templates) {
    echo $templates->render('v1/user');
});
/*** PROFILE */
$router->match('POST|GET', '/v1/user/me', function() use ($templates) {
    echo $templates->render('v1/user-me');
});
$router->match('POST|GET', '/v1/user/profile', function() use ($templates) {
    echo $templates->render('v1/user-profile');
});
$router->match('POST|GET', '/v1/user/foto', function() use ($templates) {
	echo $templates->render('v1/user-foto');
});
$router->match('POST|GET', '/v1/user/lokasi', function() use ($templates) {
	echo $templates->render('v1/user-lokasi');
});
$router->match('POST|GET', '/v1/user/lat-lng', function() use ($templates) {
	echo $templates->render('v1/user-latlng');
});
$router->match('POST|GET', '/v1/user/username', function() use ($templates) {
	echo $templates->render('v1/user-username');
});
$router->match('POST|GET', '/v1/user/password', function() use ($templates) {
	echo $templates->render('v1/user-password');
});

/*** GET */
$router->match('POST|GET', '/v1/user/(:num)', function($id) use ($templates) {
    $info = array(
		'id' 	=> $id
	);
	$templates->addData($info);
	echo $templates->render('v1/user-id');
});
$router->match('POST|GET', '/v1/nama/(:num)', function($id) use ($templates) {
    $info = array(
		'id' 	=> $id
	);
	$templates->addData($info);
	echo $templates->render('v1/user-nama');
});
$router->match('POST|GET', '/v1/user/(:num)/(:num)', function($offset, $limit) use ($templates) {
    $info = array(
		'offset' 	=> $offset,
		'limit' 	=> $limit
	);
	$templates->addData($info);
	echo $templates->render('v1/user');
});

// Mail SMTP
$router->match('POST|GET', '/v1/feedback', function() use ($templates) {
    echo $templates->render('v1/feedback');
});

// Tanam & Panen
$router->match('POST|GET', '/v1/tanam', function() use ($templates) {
    echo $templates->render('v1/tanam');
});
$router->match('POST|GET', '/v1/panen', function() use ($templates) {
    echo $templates->render('v1/panen');
});

// Kalendar
$router->match('POST|GET', '/v1/kalendar', function() use ($templates) {
    echo $templates->render('v1/kalendar');
});
$router->match('POST|GET', '/v1/kalendar/jadwal', function() use ($templates) {
    echo $templates->render('v1/kalendar-jadwal');
});
$router->match('POST|GET', '/v1/kalendar/(:num)', function($id) use ($templates) {
	$info = array(
		'id' 	=> $id
	);
	$templates->addData($info);
    echo $templates->render('v1/kalendar-id');
});
$router->match('POST|GET', '/v1/kalendar/tanggal', function() use ($templates) {
	echo $templates->render('v1/kalendar-tanggal');
});
$router->match('POST|GET', '/v1/kalendar/panen', function() use ($templates) {
	echo $templates->render('v1/kalendar-panen');
});
$router->match('POST|GET', '/v1/kalendar/add', function() use ($templates) {
	echo $templates->render('v1/kalendar-add');
});