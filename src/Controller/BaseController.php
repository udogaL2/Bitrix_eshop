<?php

namespace App\Src\Controller;

use App\Config\Config;

abstract class BaseController
{
	public function view(string $path, array $args = []) : string
	{
		if (preg_match('#^[0-9A-Za-z-_/]+$#', $path))
		{
			throw new PathException('invalid page address');
		}

		$path = Config::ROOT . "/src/View/$path.php";

		extract($args, EXTR_OVERWRITE);

		ob_start();
		require $path;
		return ob_get_clean();
	}
}

class PathException extends \Exception
{}