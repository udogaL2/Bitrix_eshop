<?php

namespace App\Src\Service;

use App\Config\Config;
use App\Src\DAO\GoodDAO;
use App\Src\DAO\OrderDAO;
use App\Src\DAO\TagDAO;

class AdminService
{
	public static function getContentBySection(string $section)
	{
		if ($section === 'tags')
		{
			return TagDAO::getAllTags();
		}

		if ($section === 'goods')
		{
			return GoodDAO::getAllGoods();
		}

		if ($section === 'orders')
		{
			$orders = OrderDAO::getAllOrders();
            $goodsNameAndStatus = [];
            foreach ($orders as $order)
            {
                $good = GoodDAO::getCurrentGoodById($order->getGoodId());
                if ($good !== null)
                {
                    $goodsNameAndStatus[] = [
                        'goodName' => $good->getName(),
                        'status' => $order->getStatus(),
                        ];
                }
            }
            return $goodsNameAndStatus;
		}


		return [];
	}
}