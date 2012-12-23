<?php

require 'autoload.php';

use Traffic\Router;

$router = new Router([
	[':^/$:', 'Site', 'index'],
	// These don't actually exist.
	[':^/latest$:', 'Files', 'latest'],
	[':^/best$:', 'Files', 'best'],
]);

$uri = $_SERVER['REQUEST_URI'];

$res = $router->handle($uri);

// Copy array into locals.
extract($res->vars);

include 'templates/' . $res->template;
