<?php

namespace App\Src\Controller;

use App\Src\Model\Good;
use App\Config\Config;
use App\Src\DAO\ImageDAO;
use App\Src\DAO\GoodDAO;
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

		$preparedGoodsIds = join(",", $goodIds);
		$images = ImageDAO::getImageOfGoods($preparedGoodsIds, true);
		if (!$images)
		{
			return null;
		}

		foreach ($goodIds as $goodId)
		{
			$listOfGoods[$goodId]->setImages($images[$goodId] ?? []);
		}

		return $listOfGoods;
	}
}