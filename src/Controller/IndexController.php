<?php

namespace App\Src\Controller;

use App\Src\Service\IndexService;
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
	public function viewGoodByPage(int $page = 1)
	{
		try
		{
            $service = new IndexService();
			$goods = $service->getGoodsByPage($page);
			if (!isset($goods))
			{
				throw new PathException("Page not found");
			}

            $lastPage = $service->getLastPageForPagination();
			$pages = $service->getPagesForPaginationByPage($page);

			echo self::view('Main/index.html', [
				'content' => self::view('Good/good.html', [
					'goods' => $goods,
					'pages' => $pages,
                     'currentPage' => $page,
                     'lastPage' => $lastPage,
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