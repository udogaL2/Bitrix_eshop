<?php

namespace App\Src\DAO;

use App\Core\Database\Service\DBSession;
use App\Src\Model\Order;
use App\Src\Model\Customer;
use Exception;

class OrderDAO
{
	public static function createOrder(Order $orderInfo, Customer $customerInfo): bool
	{
		try
		{
			DBSession::requestDB(
				"INSERT INTO `order` (good_id, date_create, c_name, c_phone, c_email, c_wish, status, address, price)
					VALUE (?, ?, ?, ?, ?, ?, ?, ?, ?);",
				'isssssssd',
				[
					$orderInfo->getGoodId(),
					date('Y-m-d H:i:s'),
					$customerInfo->getName(),
					$customerInfo->getPhone(),
					$customerInfo->getEmail(),
					$customerInfo->getWish(),
					'new',
					$orderInfo->getAddress(),
					$orderInfo->getPrice(),
				]
			);

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}
}