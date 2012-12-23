<?php
namespace Traffic;

class Router
{
	private $routes = array();
	
	public function route($patern, $controller, $method)
	{
		$this->routes[] = [
			'pattern' => $patern,
			'controller' => $controller,
			'method' => $method
		];
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
				return $this->doRoute($route, $args);
			}
		}
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
