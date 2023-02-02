<?php

namespace App;

require_once __DIR__ . '/../config/Config.php';

class Application
{
	public static function run(): void
	{
		self::autoload();

		$router = new \App\Core\Routing\Router();
		$route = $router->find($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

		if ($route)
		{
			$action = $route->getAction();
			$variables = $route->getVariables();
			echo $action(...$variables);
		}
		else
		{
			http_response_code(404);
			echo 'page not found';
			exit;
		}
	}

	public static function autoload(): void
	{
		spl_autoload_register(static function($class) {
			$prefix = 'App\\';
			$base_dir = \App\Config\Config::ROOT;

			if (strpos($class, "App") !== 0)
			{
				return;
			}

			$relativePath = str_replace($prefix, "/", $class);

			$file = $base_dir . $relativePath . '.php';
			if (file_exists($file))
			{
				require_once $file;
			}
		});
	}
}