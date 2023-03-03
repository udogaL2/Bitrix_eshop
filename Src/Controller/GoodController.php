<?php

namespace App\Src\Controller;

use App\Src\DAO\GoodDAO;
use App\Src\Service\GoodService;

class GoodController extends BaseController
{
	public function getDetailedGoodAction($id) : void
	{
        AuthController::adminSessionAction();

        $service = new GoodService(new \DateInterval('P24M'));
		// TODO(переделать проверку на наличие товара в БД, при удалении того или иного товара, то есть при нарушении ai)
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
                'isAdmin' => false,
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