<?php

namespace App\Src\Controller;

use App\Src\DAO\GoodDAO;

class GoodController extends BaseController
{
	public function getDetailedGoodAction($id)
	{
		// TODO(сделать запись/чтение количества товаров в кеш)
		if ($id < 0 || $id >= GoodDAO::getAvailableCount())
		{
			$this->notFoundAction();

			return;
		}

		try
		{
			$good = GoodDAO::getCurrentGoodById($id);

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