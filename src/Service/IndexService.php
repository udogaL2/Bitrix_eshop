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

	private function updateCache(): void
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
	private function getListOfGoodsByPage(string $tagsFromGet, int $page = 1, string $searchSubstr = ""): ?array
	{
		$countGoodsOnPage = Config::COUNT_GOODS_ON_PAGE;
		$offsetByPage = ($page - 1) * $countGoodsOnPage;

		if (empty($tagsFromGet) && empty($searchSubstr))
		{
			$this->updateCache();

			if ($page < 0 || $offsetByPage > self::$allCountOfGoodsCache)
			{
				return null;
			}

			return GoodDAO::getAvailableGoodsByOffset($offsetByPage);
		}

		$tagsFromGetToArrayInt = $this->arrayStringIdToInt(explode(" ", $tagsFromGet));
		$countOfGoods = GoodDAO::getAvailableCount($tagsFromGetToArrayInt, $searchSubstr);

		if ($page < 0 || $offsetByPage > $countOfGoods)
		{
			return null;
		}

		return GoodDAO::getAvailableGoodsByOffset($offsetByPage, $tagsFromGetToArrayInt, $searchSubstr);
	}

	/**
	 * @throws PathException
	 */
	private function arrayStringIdToInt(array $str): ?array
	{
		if ($str === [""])
		{
			return [];
		}

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
	 * @throws PathException
	 */
	public function getGoodsByPage(int $page = 1, string $tagsFromGet = "", string $searchSubstr = ""): ?array
	{


		$listOfGoods = $this->getListOfGoodsByPage($tagsFromGet, $page, $searchSubstr);

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
	public function getLastPageForPagination(string $tagsFromGet = "", string $searchSubstr = ""): int
	{
		if (empty($tagsFromGet) && empty($searchSubstr))
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
			$countGoods = GoodDAO::getAvailableCount($tagsFromGetToArrayInt, $searchSubstr);
		}

		return (int)ceil($countGoods / Config::COUNT_GOODS_ON_PAGE);
	}

	public function getPagesForPaginationByPage(
		int    $currentPage,
		string $tagsFromGet = "",
		string $searchSubstr = ""
	): ?array
	{
		$pages = [];
		$lastPage = $this->getLastPageForPagination($tagsFromGet, $searchSubstr);

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

	public static function getTagIDifSearchQueryIsTag(string $searchQuery): int|bool
	{
		if (!$searchQuery){
			return false;
		}
		return TagDAO::getTagIdLikeSubstr($searchQuery) ?? false;
	}

	public static function stripData(string $data): string
	{
		$data = strip_tags($data);
		$data = htmlspecialchars($data);

		$quotes = ["\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!"];
		$goodQuotes = ["-", "+", "#"];
		$repQuotes = ["\-", "\+", "\#"];
		$text = trim(strip_tags($data));
		$text = str_replace($quotes, '', $text);
		$text = str_replace($goodQuotes, $repQuotes, $text);

		return mb_ereg_replace(" +", " ", $text);
	}
}