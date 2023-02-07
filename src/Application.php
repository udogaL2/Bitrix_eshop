<?php

namespace App;

use App\Config\Config;
use App\Core\Database\Migration\Migrator;

require_once __DIR__ . '/../config/Config.php';
require_once __DIR__ . '/../core/Database/Migration/Migrator.php';
require_once __DIR__ . '/../core/Database/Service/DB_session.php';

class Application
{
	public static function run(): void
	{
		if (Config::IS_DEV_ENVIRONMENT)
			Migrator::migrate();

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
			$router->notFound();
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