<?php

namespace App\Core\Routing;

use App\Config\Config;

class Router
{
	/** @var Route[] $routes */
	private array $routes;
	private string $config = Config::ROOT . '/config/router.php';

	public function __construct()
	{
		if (!file_exists($this->config))
		{
			throw new \Exception('no config file by path: ' . $this->config);
		}
		foreach (require $this->config as $route)
		{
			$this->add($route['method'], $route['path'], $route['action']);
		}
	}

	public function add(string $method, string $URI, callable $action) : void
	{
		$this->routes[] = new Route($method, $URI, \Closure::fromCallable($action));
	}

	public function get(string $URI, callable $action) : void
	{
		$this->add('GET', $URI, $action);
	}

	public function post(string $URI, callable $action) : void
	{
		$this->add('POST', $URI, $action);
	}

    public function update() : void
    {
        $this->routes = [];
        foreach (require $this->config as $route)
        {
            $this->add($route['method'], $route['path'], $route['action']);
        }
    }

    public function internalSearch($method, $URI) : ?Route
    {
        [$path] = explode('?', $URI);
        foreach ($this->routes as $route)
        {
            if ($route->getMethod() !== $method)
            {
                continue;
            }

            if ($route->match($path))
            {
                header('HTTP/1.1 200 OK');
                header('Status: 200 OK');
                return $route;
            }
        }
        return null;
    }

	public function find($method, $URI) : ?Route
	{
        $route = $this->internalSearch($method, $URI);
        if ($route !== null)
        {
            return $route;
        }
        $this->update();
		return $this->internalSearch($method, $URI);
	}

	// necessity?
	public function remove($method, $URI) : bool
	{
		[$path] = explode('?', $URI);
		foreach ($this->routes as $key => $route)
		{
			if ($route->getMethod() !== $method)
			{
				continue;
			}

			if ($route->match($path))
			{
				unset($this->routes[$key]);
				return true;
			}
		}
		return false;
	}

	public function notFound() : void
	{
		http_response_code(404);
		$host = 'http://'.$_SERVER['HTTP_HOST'];
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:' . $host . '/error/404/');
	}

    public function fatalError() : void
    {
        http_response_code(503);
        $host = 'http://'.$_SERVER['HTTP_HOST'];
        header('HTTP/1.1 503 Service Unavailable');
        header('Status: 503 Service Unavailable');
        header('Location:' . $host . '/fatal/');
    }

	// public function saveRoutes() : void
	// {
	// 	file_put_contents($this->config, 'return ' . var_export($this->routes, true) . ';');
	// }
}