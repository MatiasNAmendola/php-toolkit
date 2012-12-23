<?php
namespace Traffic;

class Router
{
	private $routes = array();
	
	public function __construct($routes = NULL)
	{
		if(is_array($routes))
		{
			$this->routes($routes);
		}
	}
	
	public function route($patern, $controller, $method)
	{
		$this->routes[] = [
			'pattern' => $patern,
			'controller' => $controller,
			'method' => $method
		];
	}
	
	public function routes($routes)
	{
		foreach($routes as $route)
		{
			$this->route($route[0], $route[1], $route[2]);
		}
	}
	
	public function handle($path)
	{
		foreach($this->routes as $route)
		{
			$matches = array();
			if(preg_match($route['pattern'], $path, $matches ) )
			{
				// Catch any extra parameters.
				$args = array_slice(func_get_args(), 1);
				$args = array_merge($args, array_slice($matches, 1));
				return $this->doRoute($route, $args);
			}
		}
		print_r('Failed to route');
	}
	
	public function doRoute(&$route, &$args)
	{
		// Create the controller.
		$name = $route['controller'];
		$mthd = $route['method'];
		$ctrl = new $name;
		return call_user_method_array($mthd, $ctrl, $args);
	}
}
