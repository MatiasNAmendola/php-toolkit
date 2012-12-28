# Traffic
Traffic is a simple tool for routing Paths (/some/path) to Controllers (or views or whatever you want to call them).

Simlpy give Traffic a list of URLs, along with the class methods that handle that path and it will do the work of resolving which URL was hit as well as pass in any URL parameters captured.

## Example

### Set up routing table

```php
<?php

$router = new Router([
	['|^test/$|', 'Test', 'demo'],
	['|^say/(.+)/$|', 'Test', 'repeat']
]);
```

Each route is identified by a regular expression, which allows a lot of flexibility.

### Routing

```php
<?php

$router->handle('test/', 'extra info');
```
Just calling handle() on the router with a URL is enough to get it rolling. You can also pass extra parmeters to handle() (such as client session info) and they will be passed in before any URL parameters to the view.

### View
```php
<?php 

class Auth {
	public function logout($session_info)
	{
        //...
	}
}

// $session_info will be passed into whatever view matches '/logout'
$router->handle('/logout', $session_info);

```
