<?php

namespace App\Src\Service;

use App\Config\Config;
use App\Src\DAO\GoodDAO;
use App\Src\DAO\OrderDAO;
use App\Src\DAO\TagDAO;
use App\Src\Model\Good;
use App\Src\Model\Order;
use App\Src\Model\Tag;

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

    public static function updateTag(Tag $tag)
    {
        //TagDAO::updateTag();
    }

    public static function updateGood(Good $good)
    {
        //TagDAO::updateGood();
    }

    public static function updateOrder(Order $order)
    {
        //TagDAO::updateGood();
    }
}