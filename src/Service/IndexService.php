<?php

namespace App\Src\Service;

use App\Config\Config;
use App\Src\Controller\PathException;
use App\Src\DAO\GoodDAO;
use App\Src\DAO\ImageDAO;
use App\Src\DAO\TagDAO;
use App\Src\Model\Good;

class IndexService
{
	private static int $allCountOfGoodsCache;
	private static \DateTime $cacheExpires;

	public function __construct($TTL = null)
	{
		$TTL = $TTL ?? (new \DateInterval('P0M'));

		self::$allCountOfGoodsCache = GoodDAO::getAvailableCount();
		self::$cacheExpires = (new \DateTime())->add($TTL);
	}

	private function updateCache():void
	{
		if (self::$cacheExpires < (new \DateTime()))
		{
			self::$allCountOfGoodsCache = GoodDAO::getAvailableCount();
		}
	}

	/**
	 * @return ?Good[]
	 * @throws PathException
	 */
	private function getListOfGoodsByPage(string $tagsFromGet, int $page = 1): ?array
	{
		$countGoodsOnPage = Config::COUNT_GOODS_ON_PAGE;
		$offsetByPage = ($page - 1) * $countGoodsOnPage;

		if (empty($tagsFromGet))
		{
			$this->updateCache();

			if ($page < 0 || $offsetByPage > self::$allCountOfGoodsCache)
			{
				return null;
			}

			return GoodDAO::getAvailableGoodsByOffset($offsetByPage);
		}

		$tagsFromGetToArrayInt = $this->arrayStringIdToInt(explode(" ", $tagsFromGet));
		$countOfGoods = GoodDAO::getAvailableCount($tagsFromGetToArrayInt);

		if ($page < 0 || $offsetByPage > $countOfGoods)
		{
			return null;
		}

		return GoodDAO::getAvailableGoodsByOffset($offsetByPage, $tagsFromGetToArrayInt);
	}

	/**
	 * @throws PathException
	 */
	private function arrayStringIdToInt(array $str): ?array
	{
		$arrayOfInt = [];

		foreach ($str as $element)
		{
			if (!preg_match('/^\d+$/', $element))
			{
				throw new PathException("Array of id contains invalid characters");
			}
			$arrayOfInt[] = (int)$element;
		}

		return $arrayOfInt;
	}

	/**
	 * @return ?Good[]
	 */
	public function getGoodsByPage(int $page = 1, string $tagsFromGet = ""): ?array
	{
		$listOfGoods=$this->getListOfGoodsByPage($tagsFromGet, $page);

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

	/**
	 * @throws PathException
	 */
	public function getLastPageForPagination(string $tagsFromGet = ""): int
	{
		if (empty($tagsFromGet))
		{
			if (self::$cacheExpires < (new \DateTime()))
			{
				self::$allCountOfGoodsCache = GoodDAO::getAvailableCount();
			}
			$countGoods = self::$allCountOfGoodsCache;
		}
		else
		{
			$tagsFromGetToArrayInt = $this->arrayStringIdToInt(explode(" ", $tagsFromGet));
			$countGoods = GoodDAO::getAvailableCount($tagsFromGetToArrayInt);
		}

		return (int)ceil($countGoods / Config::COUNT_GOODS_ON_PAGE);
	}

	public function getPagesForPaginationByPage(int $currentPage, string $tagsFromGet = ""): ?array
	{
		$pages = [];
		$lastPage = $this->getLastPageForPagination($tagsFromGet);

		if ($currentPage > $lastPage || $currentPage < Config::FIRST_PAGE_ON_PAGINATION)
		{
			return null;
		}

		$pages[] = $currentPage;

		$indentInOnePart = intdiv(Config::COUNT_PAGES_ON_PAGINATION, 2);

		$leftIndent = 0;
		$rightIndent = 0;
		for ($indent = 1; $indent <= Config::COUNT_PAGES_ON_PAGINATION - 1; ++$indent)
		{
			$pageLeft = $currentPage - $indent;
			$pageRight = $currentPage + $indent;
			if ($pageLeft >= Config::FIRST_PAGE_ON_PAGINATION)
			{
				$pages[] = $pageLeft;
				$leftIndent = $indent;
			}
			if ($pageRight <= $lastPage)
			{
				$pages[] = $pageRight;
				$rightIndent = $indent;
			}
		}

		$checkLeftPart = false;
		$checkRightPart = false;
		if ($leftIndent >= $indentInOnePart)
		{
			$checkLeftPart = true;
		}
		if ($rightIndent >= $indentInOnePart)
		{
			$checkRightPart = true;
		}

		sort($pages);

		if ($checkLeftPart && $checkRightPart)
		{
			$pages = array_slice($pages, $leftIndent - $indentInOnePart);

			if ($rightIndent - $indentInOnePart !== 0)
			{
				array_splice($pages, -($rightIndent - $indentInOnePart));
			}
		}

		return $pages;
	}
}