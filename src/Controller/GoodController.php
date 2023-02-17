<?php

namespace App\Src\Controller;

use App\Src\DAO\GoodDAO;
use App\Src\Service\GoodService;

class GoodController extends BaseController
{
	public function getDetailedGoodAction($id) : void
	{
        $service = new GoodService(new \DateInterval('P24M'));
		// TODO(сделать запись/чтение количества товаров в кеш)
		if ($id < 0 || $id > $service->getNumberOfGoods())
		{
			$this->notFoundAction();

			return;
		}

		try
		{
			$good = $service->getGoodInfo($id);

			if (!$good)
			{
				$this->notFoundAction();
				return;
			}

			echo self::view('Main/index.html', [
				'content' => self::view('Detail/detail.html', [
					'good' => $good,
				]),
			]);
		}
		catch (PathException $e)
		{
			//Logger::addError($e->getMessage());
			$this->notFoundAction();
			return;
		}
	}
}