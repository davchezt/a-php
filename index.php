<?php
header('Access-Control-Allow-Origin: *');

set_time_limit(0);
define('__AGRIFARM', true);

session_name('__AGRIFARM_SESSION');
if (!session_id())
    session_start();

define('__PATH', realpath(dirname(__FILE__)));
define('__INC', __PATH . '/include');
define('__LIB', __PATH . '/include/lib');
define('__MOD', __PATH . '/addon/routers');
define('__THM', __PATH . '/themes');
define('__DTA', __PATH . '/data');
define('__EXT', __PATH . '/addon/extension');

include_once __INC . '/functions.php';
include_once __LIB . '/Page.php';
include_once __LIB . '/R.php';
include_once __LIB . '/Image.php';
// include_once __INC . '/ses.php';
include_once __LIB . '/Db.php';
include_once __LIB . '/Router.php';
include_once __LIB . '/plates/autoload.php';
include_once __INC . '/sql.php';
include_once __PATH . '/vendor/autoload.php';

R::set('config', include_once __PATH . '/configs.php');
$config = R::get('config');

if (strpos($_SERVER['REQUEST_URI'], 'admin') !== false)
{
    $active_template = "/admin";
}
else {
    $active_template = '/default';
}

R::set('page', new Paging());
R::set('router', new Router());
R::set('templates',  new Template\Engine('themes'.$active_template));

$router = R::prop('router');
$templates = R::prop('templates');
$webPath = $router->webPath();

R::set('path', $webPath);
if (strlen($webPath) > 1)
{
	R::set('path', $webPath . '/');
}
define('__URL', R::get('path'));

$templates->loadExtension(new Template\Extension\Asset('themes'.$active_template));
$templates->loadExtension(new Template\Extension\AssetCssJs());

// Load Extentions
$exts = listingDir(__EXT);
if (count($exts) != 0) {
	foreach($exts['files'] as $ext) {
		if (file_exists($ext['location'] . '/' . $ext['file'])) {
			include_once $ext['location'] . '/' . $ext['file'];
			$nama = pathinfo($ext['file'], PATHINFO_FILENAME);
			$ext_name = ucfirst($nama);
			$templates->loadExtension(new $ext_name());
		}
	}
}

// Load Routers
$modules = listingDir(__MOD);
if (count($modules) != 0)
{
	foreach ($modules['files'] as $module) {
		include_once $module['location'] . '/' . $module['file'];
	}
}

$router->set404(function() use ($templates) {
	header('HTTP/1.1 404 Not Found');
	$templates->addData(array(
        'page_id'   => '404',
		'page_title' => 'Halaman Tidak Ditemukan',
		'page_desc' => 'Error 404: Halaman Tidak Ditemukan'
	));
    echo $templates->render('404');
});

$router->before('GET|POST', '/(:all)', function() use ($config) {
 	if (isset($config['maintenance']) and $config['maintenance'] == 'Y') {
		echo 'Maintenance';
		exit();
	}
});

$router->run(function() use ($router, $templates) {
    return $templates;
});

// EOL