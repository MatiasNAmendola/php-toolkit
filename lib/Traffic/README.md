# Traffic
Traffic is a simple tool for routing Paths (/some/path) to Controllers (or views or whatever you want to call them).

Simlpy give Traffic a list of URLs, along with the class methods that handle that path and it will do the work of resolving which URL was hit as well as pass in any URL parameters captured.

## Example

### Set up routing table

```php
<?php

$router = new Router([
	['|^/$|', 'Files', 'index'],
	['|^latest/$|', 'Files', 'latest']
]);
```
Each route contains a Regular Expression, a Class name and a Method name. 

### Routing

```php
<?php

$response = $router->handle('latest/', 'extra info');
```
The handle() method resolves a URL to one of the routes the router knows about, testing the given URL against the routing table until it finds a match (or runs out of routes). Once a match is found it instanciates the class and calls the method, passing in any groups matched in the regex.

### Responses
Traffic returns the exact value that came out of the view it called, so you can return anything you like. However a Response class is included which contains a 'template' and a 'vars' field, which should suffice for the simplest cases.

```php
<?php
use Traffic\Response;

class Files
{
	public function latest()
	{
		return new Response('lastest_files.php', 
		[
			'latest' => /* magic database query */,
		]);
	}
}
```
The response object can then be used to load the appropriate template with the correct values.

### Example
```php
<?php 

use Traffic\Response;

class Profile {
	public function view($session_info, $section)
	{
        //...
        return new Response("profile_$section.php", ['profile' => /* ... */ ] );
	}
}

$router = new Router([
    /* The group in this regex will be passed into the 'view' method. */
	['|^profile/(\w+)/?$|', 'Profile', 'view'],
]);

// $session_info will be passed into whatever view matches the URL.
$router->handle('/profile/contact', $session_info);

```
