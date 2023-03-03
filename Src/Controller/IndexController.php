<?php

namespace App\Src\Controller;

use App\Src\DAO\TagDAO;
use App\Src\Service\IndexService;
use Exception;

class IndexController extends BaseController
{
	public function viewGoodByPage($page = 1): void
	{
		if (!is_numeric($page))
		{
			$this->notFoundAction();
			return;
		}

		AuthController::adminSessionAction();
		try
		{
			$service = new IndexService();

			$searchTags = $_GET["tags"] ?? '';
			$searchQuery = $_GET["search_query"] ?? '';

			if ($searchQuery)
			{
				$searchQuery = IndexService::stripData($searchQuery);

				if (mb_strlen($searchQuery) < 2)
				{
					throw new PathException("Array of id contains invalid characters");
				}
				elseif (mb_strlen($searchQuery) > 128)
				{
					throw new PathException("Array of id contains invalid characters");
				}
			}

			if (empty($searchTags) && empty($searchQuery))
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
				$searchQueryTag = IndexService::getTagIDifSearchQueryIsTag($searchQuery);

				if ($searchQueryTag)
				{
					header("Location: /?tags={$searchQueryTag}");
				}

				$goods = $service->getGoodsByPage($page, $searchTags, $searchQuery);
				if (!isset($goods))
				{
					throw new PathException("Page not found");
				}

				$tags = TagDAO::getAllTags();
				$lastPage = $service->getLastPageForPagination($searchTags, $searchQuery);
				$pages = $service->getPagesForPaginationByPage($page, $searchTags, $searchQuery);
			}

			echo self::view('Main/index.html', [
				'content' => self::view('Good/good.html', [
					'goods' => $goods,
					'pages' => $pages,
					'currentPage' => $page,
					'lastPage' => $lastPage,
					'tags' => $tags,
					'searchQuery' => $searchQuery,
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