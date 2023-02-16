<?php

namespace App\Src\Service;

use App\Config\Config;
use App\Src\DAO\GoodDAO;
use App\Src\DAO\ImageDAO;
use App\Src\DAO\TagDAO;
use App\Src\Model\Good;

class IndexService
{
	/**
	 * @return ?Good[]
	 */
	public static function getGoodsByPage(int $page = 1): ?array
	{
		$countGoodsOnPage = Config::COUNT_GOODS_ON_PAGE;
		$offsetByPage = ($page - 1) * $countGoodsOnPage;

		// TODO(сделать запись/чтение количества товаров в кеш)
		if ($page < 0 || $offsetByPage > GoodDAO::getAvailableCount())
		{
			return null;
		}

		$listOfGoods = GoodDAO::getAvailableGoodsByOffset($offsetByPage);
		if (!$listOfGoods)
		{
			return null;
		}

		$goodIds = array_keys($listOfGoods);
		$images = ImageDAO::getImageOfGoods($goodIds, true);
		$tags = TagDAO::getTagsOfGoods($goodIds);

		if (!$images || !$tags)
		{
			return null;
		}

		foreach ($goodIds as $goodId)
		{
			$listOfGoods[$goodId]->setImages($images[$goodId] ?? []);
			$listOfGoods[$goodId]->setTags($tags[$goodId] ?? []);
		}

		return $listOfGoods;
	}

	public static function getLastPageForPagination():int
	{
		$countGoods=GoodDAO::getAvailableCount();
		return (int)ceil($countGoods/Config::COUNT_GOODS_ON_PAGE);
	}

	public static function getPagesForPaginationByPage(int $currentPage): ?array
	{
		$pages=[];
		$lastPage=self::getLastPageForPagination();

		if ($currentPage>$lastPage || $currentPage<Config::FIRST_PAGE_ON_PAGINATION)
		{
			return null;
		}

		$pages[]=$currentPage;

		$indentInOnePart=intdiv(Config::COUNT_PAGES_ON_PAGINATION,2);

		$leftIndent=0;
		$rightIndent=0;
		for ($indent=1; $indent<=Config::COUNT_PAGES_ON_PAGINATION-1; ++$indent){
			$pageLeft=$currentPage-$indent;
			$pageRight=$currentPage+$indent;
			if ($pageLeft>=Config::FIRST_PAGE_ON_PAGINATION)
			{
				$pages[]=$pageLeft;
				$leftIndent=$indent;
			}
			if ($pageRight<=$lastPage)
			{
				$pages[]=$pageRight;
				$rightIndent=$indent;
			}
		}

		$checkLeftPart=false;
		$checkRightPart=false;
		if ($leftIndent>=$indentInOnePart)
		{
			$checkLeftPart = true;
		}
		if ($rightIndent>=$indentInOnePart)
		{
			$checkRightPart = true;
		}

		sort($pages);

		if ($checkLeftPart && $checkRightPart)
		{
			$pages=array_slice($pages, $leftIndent-$indentInOnePart);

			if ($rightIndent-$indentInOnePart!==0)
			{
				array_splice($pages, -($rightIndent - $indentInOnePart));
			}
		}

		return $pages;
	}
}