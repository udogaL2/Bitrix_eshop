<?php

namespace App;

use App\Config\Config;
use App\Core\Database\Migration\Migrator;
use App\Src\Controller\PathException;
use \App\Core\Routing\Router;

require_once __DIR__ . '/../config/Config.php';
//require_once __DIR__ . '/../core/Database/Migration/Migrator.php';
//require_once __DIR__ . '/../core/Database/Service/DBSession.php';


class Application
{
	public static function run(): void
	{
        // TODO: implement error handlers
        //register_shutdown_function("self::fatalHandler");
        //set_error_handler();

        try
        {
            self::autoload();
        }
        catch (\Exception $e)
        {
            //Logger::addError($e->getLine(), ': ', $e->getMessage());
            echo "Server temporary unavailable";
            return;
        }
        $router = new Router();


        if (Config::IS_DEV_ENVIRONMENT)
        {
            try {
                Migrator::migrate();
            }
            catch (\Exception $e)
            {
                //Logger::addError($e->getLine(), ': ', $e->getMessage());
                echo $e->getLine();
                $router->fatalError();
                return;
            }
        }

		$route = $router->find($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

		if (!$route)
		{
            $router->notFound();
            return;
		}

        $action = $route->getAction();
        $variables = $route->getVariables();
        echo $action(...$variables);

	}

	public static function autoload(): void
	{
		spl_autoload_register(static function($class) {
			$prefix = 'App\\';
			$base_dir = Config::ROOT;

			if (!str_starts_with($class, "App"))
			{
				return;
			}

			$relativePath = str_replace($prefix, "/", $class);

			$file = $base_dir . $relativePath . '.php';
			if (!file_exists($file))
			{
				throw new \Exception("cannot find class $class by path: $file");
			}
            require_once $file;
		});
	}

    private static function fatalHandler() : void
    {
        $errFile = "unknown file";
        $errStr  = "shutdown";
        $errno   = E_CORE_ERROR;
        $errLine = 0;

        $error = error_get_last();

        if($error !== NULL) {
            $errno   = $error["type"];
            $errFile = $error["file"];
            $errLine = $error["line"];
            $errStr  = $error["message"];
        }
        echo $errno, $errStr, $errFile, $errLine;
    }
}