<?php

namespace App\Src\Controller;

use App\Config\Config;
use App\Src\DAO\TagDAO;

abstract class BaseController
{
	public static function view(string $path, array $args = []) : string
	{
		if (preg_match('#^[0-9A-Za-z-_/]+$#', $path))
		{
			throw new PathException('invalid page address');
		}

		$path = Config::ROOT . "/Src/View/$path.php";

		extract($args, EXTR_OVERWRITE);

		ob_start();
		require $path;
		return ob_get_clean();
	}

	public function goodsNotFoundAction():void
	{
		echo self::view('Main/index.html', [
			'content' => config::GOODS_NOT_FOUND,
			'isAdmin' => false,
		]);
	}

	public function notFoundAction() : void
	{
		echo self::view('service/404.html');
	}

    public function internalErrorAction() : void
    {
        echo self::view('service/fatal.html');
    }
}

class PathException extends \Exception
{}