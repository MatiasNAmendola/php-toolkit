<?php
include('../autoload.php');

use Traffic\Router;

class Test {
	public function demo($info)
	{
		echo 'Hello world!' . PHP_EOL;
		echo 'Got info: ' . $info . PHP_EOL;
	}
	
	public function repeat($str)
	{
		echo 'You said: ' . $str . PHP_EOL;
	}
}

$router = new Router([
	['|^test/$|', 'Test', 'demo'],
	['|^say/(.+)/$|', 'Test', 'repeat']
]);

// Also possible to called Router::routes() with routing info.

$router->handle('test/', 'extra info');

$router->handle('say/banana/');
