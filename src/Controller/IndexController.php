<?php

namespace App\Src\Controller;

use App\Core\Database\Service\DB_session;
use App\Src\Model\Good;
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
			"SELECT * FROM good LIMIT $countGoodsOnPage OFFSET $offsetByPage;",
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
				$good["IS_ACTIVE"]
			//TODO получение картинок и тегов из БД
			);
		}

		return $goods;
	}
}