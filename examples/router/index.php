<?php
include('../autoload.php');

use Traffic\Router;

class Test {
	public function demo($info)
	{
		echo 'Hello world!' . PHP_EOL;
		echo 'Got info: ' . $info . PHP_EOL;
	}
}

$router = new Router();

$router->route(
	'|^test/$|', 'Test', 'demo'
);

$router->handle('test/', 'extra info');
