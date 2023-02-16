<?php

namespace App\Src\Controller;

use App\Src\Service\IndexService;
use Exception;

class IndexController extends BaseController
{
	public function viewGoodByPage(int $page = 1): void
	{
		try
		{
			$goods = IndexService::getGoodsByPage($page);
			if (!isset($goods))
			{
				throw new PathException("Page not found");
			}

			$pages =IndexService::getPagesForPaginationByPage($page);
			echo self::view('Main/index.html', [
				'content' => self::view('Good/good.html', [
					'goods' => $goods,
					'pages' => $pages,
				]),
			]);
		}
		catch (Exception $e)
		{
			$this->notFoundAction();
			return;
		}
	}
}