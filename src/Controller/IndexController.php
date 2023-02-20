<?php

namespace App\Src\Controller;

use App\Src\DAO\TagDAO;
use App\Src\Service\IndexService;
use App\Src\Service\TagService;
use Exception;

class IndexController extends BaseController
{
	//    public function indexPageAction(int $page = 1) : void
	//    {
	//        if(isset($_GET['search']))
	//        {
	//            var_dump($_GET['search']);
	//        }
	//        else
	//        {
	//            $this->viewGoodByPage($page);
	//        }
	//    }
	public function viewGoodByPage(int $page = 1): void
	{
		try
		{
			if (empty($_GET["tags"]))
			{
				$service = new IndexService();
				$goods = $service->getGoodsByPage($page);
				if (!isset($goods))
				{
					throw new PathException("Page not found");
				}

				$tags = TagDAO::getAllTags();
				$lastPage = $service->getLastPageForPagination();
				$pages = $service->getPagesForPaginationByPage($page);

				echo self::view('Main/index.html', [
					'content' => self::view('Good/good.html', [
						'goods' => $goods,
						'pages' => $pages,
						'currentPage' => $page,
						'lastPage' => $lastPage,
						'tags' => $tags,
					]),
				]);
			}
			else
			{
				$service = new IndexService();
				$goods = $service->getGoodsByPage($page, $_GET["tags"]);
				if (!isset($goods))
				{
					throw new PathException("Page not found");
				}

				$tags = TagDAO::getAllTags();
				$lastPage = $service->getLastPageForPagination($_GET["tags"]);
				$pages = $service->getPagesForPaginationByPage($page, $_GET["tags"]);

				echo self::view('Main/index.html', [
					'content' => self::view('Good/good.html', [
						'goods' => $goods,
						'pages' => $pages,
						'currentPage' => $page,
						'lastPage' => $lastPage,
						'tags' => $tags,
					]),
				]);
			}
		}
		catch (Exception $e)
		{
			$this->notFoundAction();

			return;
		}
	}
}