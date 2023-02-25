<?php

namespace App\Src\Controller;

use App\Src\DAO\TagDAO;
use App\Src\Service\IndexService;
use Exception;

class IndexController extends BaseController
{
	public function viewGoodByPage(int $page = 1): void
	{
        AuthController::adminSessionAction();
		try
		{
			$service = new IndexService();
			if (empty($_GET["tags"]))
			{
				$goods = $service->getGoodsByPage($page);
				if (!isset($goods))
				{
					throw new PathException("Page not found");
				}

				$tags = TagDAO::getAllTags();
				$lastPage = $service->getLastPageForPagination();
				$pages = $service->getPagesForPaginationByPage($page);
			}
			else
			{
				$goods = $service->getGoodsByPage($page, $_GET["tags"]);
				if (!isset($goods))
				{
					throw new PathException("Page not found");
				}

				$tags = TagDAO::getAllTags();
				$lastPage = $service->getLastPageForPagination($_GET["tags"]);
				$pages = $service->getPagesForPaginationByPage($page, $_GET["tags"]);
			}

			echo self::view('Main/index.html', [
				'content' => self::view('Good/good.html', [
					'goods' => $goods,
					'pages' => $pages,
					'currentPage' => $page,
					'lastPage' => $lastPage,
					'tags' => $tags,
				]),
				'isAdmin' => false,
			]);
		}
		catch (Exception $e)
		{
			$this->goodsNotFoundAction();
			return;
		}
	}
}