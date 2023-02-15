<?php

namespace App\Src\DAO;

use App\Config\Config;
use App\Core\Database\Service\DB_session;
use App\Src\Model\Good;
use App\Src\DAO\ImageDAO;
use App\Src\DAO\TagDAO;

class GoodDAO
{
	public static function getAvailableCount(): ?int
	{

		try
		{
			$res = DB_session::request_db("select COUNT(*) from good where IS_ACTIVE = true;");

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
			$goodsQuery = DB_session::request_db(
				"select * from good
                    where IS_ACTIVE = true 
					LIMIT $countGoodsOnPage OFFSET $offsetByPage;",
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

	public static function getCurrentGoodById(int $id, bool $isNotActive = false): ?Good
	{
		try
		{
			$additionalConditionForRequest = !$isNotActive ? " and IS_ACTIVE = true" : "";

			$goodRequest = DB_session::request_db(
				"SELECT * FROM good where ID = ?{$additionalConditionForRequest};", 'i', [$id]
			);

			$goodResult = mysqli_fetch_assoc($goodRequest);

			if (!$goodResult)
			{
				return null;
			}

			$images = ImageDAO::getImageOfGoods((string)$id);
			$tags = TagDAO::getTagsOfGoods((string)$id);

			if (!$images || !$tags)
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
				$goodResult['IS_ACTIVE'], (array)$images[$id], (array)$tags[$id]
			);
		}
		catch (\Exception $e)
		{
			return null;
		}

	}
}