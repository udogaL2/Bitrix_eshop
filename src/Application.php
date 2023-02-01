<?php

namespace App;

abstract class Application
{
	public static function run(): void
	{
		self::autoload();
	}

	public static function autoload(): void
	{
		spl_autoload_register(static function($class) {
			$prefix = "App\\";
			$base_dir = __DIR__ . '/';

			$len = strlen($prefix);
			if (strncmp($prefix, $class, $len) !== 0)
			{
				return;
			}

			$relative_class = substr($class, $len);

			$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

			if (file_exists($file))
			{
				require_once $file;
			}
		});
	}
}