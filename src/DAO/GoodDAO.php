<?php

namespace App\Src\DAO;

use App\Config\Config;
use App\Core\Database\Service\DBSession;
use App\Src\Model\Good;

class GoodDAO
{
	public static function getAvailableCount(): ?int
	{

		try
		{
			$res = DBSession::requestDB("select COUNT(*) from good where IS_ACTIVE = true;");

			return mysqli_fetch_array($res)[0];
		}
		catch (\Exception $e)
		{
			return null;
		}
	}

	public static function getAvailableGoodsByOffset(int $offsetByPage): ?array
	{
		try
		{

			$countGoodsOnPage = Config::COUNT_GOODS_ON_PAGE;

			$goodsQuery = DBSession::requestDB(
				"select * from good
                    where IS_ACTIVE = true 
					LIMIT ? OFFSET ?;", 'ii', [$countGoodsOnPage, $offsetByPage]
			);

			$goods = [];

			while ($good = mysqli_fetch_assoc($goodsQuery))
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

			return $goods;
		}
		catch (\Exception $e)
		{
			return null;
		}
	}

	public static function getCurrentGoodById(
		int $id,
		bool $isNotActive = false,
		bool $isWithImages = true,
		bool $isWithTags = true
	): ?Good
	{
		try
		{
			$additionalConditionForRequest = !$isNotActive ? " and IS_ACTIVE = true" : "";

			$goodRequest = DBSession::requestDB(
				"SELECT * FROM good where ID = ?{$additionalConditionForRequest};", 'i', [$id]
			);

			$goodResult = mysqli_fetch_assoc($goodRequest);

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
		catch (\Exception $e)
		{
			return null;
		}

	}
}