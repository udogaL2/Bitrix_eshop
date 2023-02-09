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
	private function getGoodsByPage(int $page = 1): ?array
	{
		$goods = null;

		if ($page < 0)
		{
			return null;
		}

		$countGoodsOnPage = Config::COUNT_GOODS_ON_PAGE;
		$offsetByPage = ($page - 1) * $countGoodsOnPage;

		$goodsQuery = DB_session::request_db(
			"SELECT basic.*, group_concat(image.ID SEPARATOR ', ') as IMAGE FROM image,
				(SELECT good.*,  group_concat(tag.NAME SEPARATOR ', ') as TAGS_NAME,
                 group_concat(tag.ID SEPARATOR ', ') as TAGS_ID FROM good
				INNER JOIN tag
				INNER JOIN good_tag gt on gt.GOOD_ID=good.ID && gt.TAG_ID=tag.ID

				GROUP BY good.ID
				LIMIT $countGoodsOnPage OFFSET $offsetByPage) as basic
				INNER JOIN good_image gi on basic.ID = gi.GOOD_ID
				WHERE image.ID=gi.IMAGE_ID
				GROUP BY basic.ID;",
		);

		while ($good = mysqli_fetch_assoc($goodsQuery))
		{
			$goods[] = new Good(
				$good["NAME"],
				$good["PRICE"],
				$good["GOOD_CODE"],
				$good["SHORT_DESC"],
				$good["FULL_DESC"],
				$good["ID"],
				new \DateTime($good["DATE_UPDATE"]),
				new \DateTime($good["DATE_CREATE"]),
				$good["IS_ACTIVE"],
				//TODO запрос в базу данных получает ID изображения для каждого товара. Преобразовать.
				(array)null,
				$this->collectToTags($good["TAGS_NAME"], $good["TAGS_ID"])
			);
		}

		return $goods;
	}

	/**
	 * @return Tag[]
	 */
	private function collectToTags(string $nameTags, string $idTags): array
	{
		$tagsName = explode(', ', $nameTags);
		$tagsId = explode(', ', $idTags);

		$tags = [];
		foreach ($tagsName as $key => $name)
		{
			$tag = new Tag($name, $tagsId[$key]);
			$tags[] = $tag;
		}

		return $tags;
	}

	private function getImageById(int $id): ?Image
	{

			$imageQuery = DB_session::request_db(
				'SELECT * FROM image WHERE image.ID=?', 'i', [$id]
			);

			$image = mysqli_fetch_assoc($imageQuery);
			if ($image === null)
			{
				return null;
			}

			//TODO Разобраться с тем, как создать Image, что за поле $name?
		return new Image();
	}
}