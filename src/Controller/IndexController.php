<?php

namespace App\Src\Controller;

use App\Core\Database\Service\DB_session;
use App\Src\Model\Good;
use App\Src\Model\Image;
use App\Src\Model\Tag;
use App\Config\Config;
use Exception;

class IndexController extends BaseController
{
	public function viewGoodByPage(int $page = 1): void
	{
		try
		{
			$goods = $this->getGoodsByPage($page);
			if (!isset($goods))
			{
				throw new PathException("Page not found");
			}

			echo self::view('Main/index.html', [
				'content' => self::view('Good/good.html', [
					'goods' => $goods,
				]),
			]);
		}
		catch (Exception $e)
		{
			$this->notFoundAction();
		}
	}

	/**
	 * @return ?Good[]
	 * @throws Exception
	 */
	public function getGoodsByPage(int $page = 1): ?array
	{
		$goods = null;

		if ($page < 0)
		{
			return null;
		}

		$countGoodsOnPage = Config::COUNT_GOODS_ON_PAGE;
		$offsetByPage = ($page - 1) * $countGoodsOnPage;

		$goodsQuery = DB_session::request_db(
			"select * from good
					LIMIT $countGoodsOnPage OFFSET $offsetByPage;",
		);

		$goodIds = [];

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
				$good["IS_ACTIVE"],
			);

			$goodIds[] = $good["ID"];
		}

		$preparedGoodsIds = join(",", $goodIds);
		$images = $this->collectToImages($preparedGoodsIds);
		$tags = $this->collectToTags($preparedGoodsIds);

		foreach ($goodIds as $goodId)
		{
			$goods[$goodId]->setTags($tags[$goodId]);
			$goods[$goodId]->setImages($images[$goodId]);
		}

		return $goods;
	}

	/**
	 * @return Tag[]|null
	 */
	private function collectToTags(string $preparedGoodsIds): ?array
	{
		try
		{
			$query = "select gt.GOOD_ID, (select t.NAME from tag t where gt.TAG_ID = t.ID) as tag
					from good_tag gt
					where gt.GOOD_ID in ({$preparedGoodsIds});";

			$res = DB_session::request_db($query);

			$goodIdTag = [];

			while ($tag = mysqli_fetch_row($res))
			{
				$goodIdTag[$tag[0]][] = new Tag($tag[1], null);
			}

			return $goodIdTag;

		}
		catch (Exception $e)
		{
			$this->notFoundAction();

			return null;
		}
	}

	/**
	 * @return ?Image[]
	 */
	private function collectToImages(string $preparedGoodsIds): ?array
	{
		try
		{
			$query = "select gi.GOOD_ID, (select i.ID from image i where gi.IMAGE_ID = i.ID) as img
						from good_image gi
						where gi.GOOD_ID in ({$preparedGoodsIds});";

			$res = DB_session::request_db($query);

			$goodIm = [];
			$imageIds = [];

			while ($good = mysqli_fetch_row($res))
			{
				$goodIm[$good[0]][] = $good[1];
				$imageIds[] = $good[1];
			}

			$preparedImagesIds = join(",", $imageIds);

			$query = "select *
					from image
					where ID in ({$preparedImagesIds});";

			$res = DB_session::request_db($query);

			while ($image = mysqli_fetch_assoc($res))
			{
				$imageIds[$image["ID"]] = new Image(
					$image["PATH"],
					$image["HEIGHT"],
					$image["WIDTH"],
					$image["IS_MAIN"],
					$image["ID"]
				);
			}

			foreach ($goodIm as &$item)
			{
				$item = array_map(fn($im_id): Image => $imageIds[$im_id], $item);
			}

			return $goodIm;
		}
		catch (Exception $e)
		{
			$this->notFoundAction();

			return null;
		}
	}
}