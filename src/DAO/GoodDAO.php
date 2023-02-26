<?php

namespace App\Src\DAO;

use App\Config\Config;
use App\Core\Database\Service\DBSession;
use App\Src\Model\Good;
use Exception;
use mysqli_result;

class GoodDAO extends BaseDAO
{
	protected static string $tableName = "good";

	public static function createGood(Good $good): int|bool
	{
		try
		{
			DBSession::requestDB(
				"insert into good (NAME, SHORT_DESC, FULL_DESC, PRICE, IS_ACTIVE, GOOD_CODE, DATE_CREATE, DATE_UPDATE)
				VALUE (?, ?, ?, ?, ?, ?, ?, ?)", "sssdisss", [
				 $good->getName(),
				 $good->getShortDesc(),
				 $good->getFullDesc(),
				 $good->getPrice(),
				 $good->isActive(),
				 $good->getArticle(),
				 $good->getTimeCreate()->format('Y-m-d H:i:s'),
				 $good->getTimeUpdate()->format('Y-m-d H:i:s'),
				]
			);

			$goodId = self::getLastCreatedId();

			return $goodId ?? false;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	// searchSubstring - для получения товаров по названию из поиска
	public static function getAvailableCount(array $tagIds = null, string $searchSubstring = ''): ?int
	{
		try
		{
			if ($tagIds)
			{
				$countTagIds = count($tagIds);
				$placeholders = str_repeat('?,', $countTagIds - 1) . '?';
				$rowOfTypes = str_repeat('i', $countTagIds);
				$listOfValues = $tagIds;

				$request = "select count(additional.ID)
						from
						(select g.ID, COUNT(gt.TAG_ID) as COINCIDENCE from good g
						inner join good_tag gt on g.ID = gt.GOOD_ID
						where gt.TAG_ID in ($placeholders) and g.IS_ACTIVE = true
						group by g.ID) as additional
						WHERE additional.COINCIDENCE=$countTagIds";

			}
			else
			{
				$request = "select COUNT(*) from good where IS_ACTIVE = true;";
				$rowOfTypes = '';
				$listOfValues = [];

				if ($searchSubstring)
				{
					$request = substr_replace(
						$request,
						" and (NAME like ? or NAME sounds like ?) ",
						strpos($request, "true") + 4,
						0
					);
					$rowOfTypes = 'ss';
					$listOfValues = ['%' . $searchSubstring . '%', $searchSubstring];
				}
			}

			$DBResponse = DBSession::requestDB($request, $rowOfTypes, $listOfValues);

			return mysqli_fetch_array($DBResponse)[0];
		}
		catch (Exception $e)
		{
			return null;
		}
	}

	// searchSubstring - для получения товаров по названию из поиска
	public static function getAvailableGoodsByOffset(
		int    $offsetByPage,
		array  $tagIds = null,
		string $searchSubstring = ''
	): ?array
	{
		try
		{
			$countGoodsOnPage = Config::COUNT_GOODS_ON_PAGE;

			if ($tagIds)
			{
				$countTagIds = count($tagIds);
				$placeholders = str_repeat('?,', $countTagIds - 1) . '?';
				$rowOfTypes = str_repeat('i', $countTagIds);
				$listOfValues = $tagIds;

				$request = "select additional.* 
						from 
						(select g.*, COUNT(gt.TAG_ID) as COINCIDENCE from good g
						inner join good_tag gt on g.ID = gt.GOOD_ID
						where gt.TAG_ID in ({$placeholders}) and g.IS_ACTIVE = true
						group by g.ID) as additional
						WHERE additional.COINCIDENCE=$countTagIds
						order by additional.ID
						LIMIT $countGoodsOnPage OFFSET $offsetByPage;";

			}
			else
			{

				$rowOfTypes = 'ii';
				$listOfValues = [$countGoodsOnPage, $offsetByPage];

				$request = "select * from good where IS_ACTIVE = true LIMIT ? OFFSET ?;";

				if ($searchSubstring)
				{
					$request = substr_replace(
						$request,
						" and (NAME like ? or NAME sounds like ?) ",
						strpos($request, "true") + 4,
						0
					);
					$rowOfTypes = 'ss' . $rowOfTypes;
					array_unshift($listOfValues, '%' . $searchSubstring . '%', $searchSubstring);
				}
			}

			$DBResponse = DBSession::requestDB($request, $rowOfTypes, $listOfValues);

			return self::prepareGoodsFromResponse($DBResponse);
		}
		catch (Exception $e)
		{
			return null;
		}
	}

	public static function getGoodsByIds(
		array $ids,
		bool  $isNotActive = false,
	): ?array
	{
		try
		{
			$placeholders = str_repeat('?,', count($ids) - 1) . '?';
			$additionalConditionForRequest = !$isNotActive ? " and IS_ACTIVE = true" : "";

			$DBResponse = DBSession::requestDB(
				"SELECT * FROM good where ID in ($placeholders){$additionalConditionForRequest};",
				str_repeat('i', count($ids)),
				$ids
			);

			return self::prepareGoodsFromResponse($DBResponse);
		}
		catch (Exception $e)
		{
			return null;
		}
	}

	public static function getCurrentGoodById(
		int  $id,
		bool $isNotActive = false,
		bool $isWithImages = true,
		bool $isWithTags = true
	): ?Good
	{
		try
		{
			$additionalConditionForRequest = !$isNotActive ? " and IS_ACTIVE = true" : "";

			$DBResponse = DBSession::requestDB(
				"SELECT * FROM good where ID = ?{$additionalConditionForRequest};", 'i', [$id]
			);

			$goodResult = mysqli_fetch_assoc($DBResponse);

			if (!$goodResult)
			{
				return null;
			}

			$images = $isWithImages ? ImageDAO::getImageOfGoods([$id])[$id] : [];
			$tags = $isWithTags ? TagDAO::getTagsOfGoods([$id])[$id] : [];

			if ($images === null || $tags === null)
			{
				return null;
			}

			return new Good(
				$goodResult['NAME'],
				$goodResult['PRICE'],
				$goodResult['GOOD_CODE'],
				$goodResult['SHORT_DESC'],
				$goodResult['FULL_DESC'],
				$goodResult['ID'],
				new \DateTime($goodResult['DATE_UPDATE']),
				new \DateTime($goodResult['DATE_CREATE']),
				$goodResult['IS_ACTIVE'],
				(array)$images,
				(array)$tags
			);
		}
		catch (Exception $e)
		{
			return null;
		}
	}

	private static function prepareGoodsFromResponse(mysqli_result $DBResponse): ?array
	{
		try
		{
			$goods = [];

			while ($good = mysqli_fetch_assoc($DBResponse))
			{
				if (!array_key_exists($good["ID"], $goods))
				{
					$goods[$good["ID"]] = new Good(
						$good["NAME"],
						$good["PRICE"],
						$good["GOOD_CODE"],
						$good["SHORT_DESC"],
						$good["FULL_DESC"],
						$good["ID"],
						new \DateTime($good["DATE_UPDATE"]),
						new \DateTime($good["DATE_CREATE"]),
						$good["IS_ACTIVE"]
					);
				}
			}

			return $goods;
		}
		catch (Exception $e)
		{
			return null;
		}
	}

	public static function getAllGoods(
		bool $isWithImages = false,
		bool $isWithTags = false
	): ?array
	{
		try
		{
			$DBResponse = DBSession::requestDB(
				"SELECT * FROM good;"
			);

			$goods = [];

			while ($good = mysqli_fetch_assoc($DBResponse))
			{
				$images = $isWithImages ? ImageDAO::getImageOfGoods([$good['ID']])[$good['ID']] : [];
				$tags = $isWithTags ? TagDAO::getTagsOfGoods([$good['ID']])[$good['ID']] : [];

				$goods[] = new Good(
					$good['NAME'],
					$good['PRICE'],
					$good['GOOD_CODE'],
					$good['SHORT_DESC'],
					$good['FULL_DESC'],
					$good['ID'],
					new \DateTime($good['DATE_UPDATE']),
					new \DateTime($good['DATE_CREATE']),
					$good['IS_ACTIVE'],
					(array)$images,
					(array)$tags
				);
			}

			return $goods;
		}
		catch (Exception $e)
		{
			return null;
		}
	}
}