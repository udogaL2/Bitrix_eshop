<?php

namespace App\Src\Controller;

use App\Core\Database\Service\DB_session;
use App\Src\Model\Good;
use App\Src\Model\Tag;
use App\Config\Config;
use Exception;

class IndexController extends BaseController
{
	public function viewGoodByPage(int $page=1): void
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
	private function getGoodsByPage(int $page=1): ?array
	{
		$goods = null;

		if ($page < 0)
		{
			return null;
		}

		$countGoodsOnPage = Config::COUNT_GOODS_ON_PAGE;
		$offsetByPage = ($page - 1) * $countGoodsOnPage;

		$goodsQuery = DB_session::request_db(
			"SELECT good.*,  group_concat(tag.NAME SEPARATOR ', ') as TAGS_NAME,
                     group_concat(tag.ID SEPARATOR ', ') as TAGS_ID FROM good
					   INNER JOIN tag
					   INNER JOIN good_tag gt on gt.GOOD_ID=good.ID && gt.TAG_ID=tag.ID
					   GROUP BY good.ID
					   LIMIT $countGoodsOnPage OFFSET $offsetByPage;",
		);

		if ($goodsQuery===null)
		{
			return null;
		}

		while ($good = mysqli_fetch_assoc($goodsQuery))
		{
			$goods[] = new Good(
				$good["NAME"],
				$good["PRICE"],
				$good["GOOD_CODE"],
				$good["SHORT_DISC"],
				$good["FULL_DISC"],
				$good["ID"],
				new \DateTime($good["DATE_UPDATE"]),
				new \DateTime($good["DATE_CREATE"]),
				$good["IS_ACTIVE"],
				(array)null,
				$this->collectToTags($good["TAGS_NAME"], $good["TAGS_ID"])
			//TODO получение картинок
			);
		}

		return $goods;
	}

	/**
	 * @return Tag[]
	 */
	private function collectToTags(string $nameTags, string $idTags): array
	{
		$tagsName=explode(', ', $nameTags);
		$tagsId=explode(', ', $idTags);

		$tags=[];
		foreach ($tagsName as $key => $name){
			$tag = new Tag($name, $tagsId[$key]);
			$tags[]=$tag;
		}

		return $tags;
	}
}