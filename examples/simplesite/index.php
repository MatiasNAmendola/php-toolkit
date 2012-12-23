<?php

if (php_sapi_name() == 'cli-server') { 
    // Replicate the effects of basic "index.php"-hiding mod_rewrite rules
    // Tested working under FatFreeFramework 2.0.6 through 2.0.12.
    $_SERVER['SCRIPT_NAME'] = str_replace(__DIR__, '', __FILE__);
    $_SERVER['SCRIPT_FILENAME'] = __FILE__;
 
    // Replicate the FatFree/WordPress/etc. .htaccess "serve existing files" bit
    $url_parts = parse_url($_SERVER["REQUEST_URI"]);
    $_req = rtrim($_SERVER['DOCUMENT_ROOT'] . $url_parts['path'], '/' . DIRECTORY_SEPARATOR);
    if (__FILE__ !== $_req && __DIR__ !== $_req && file_exists($_req)) {
        return false;    // serve the requested resource as-is.
    }
}

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
