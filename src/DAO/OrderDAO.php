<?php

namespace App\Src\DAO;

use App\Core\Database\Service\DBSession;
use App\Src\Model\Order;
use App\Src\Model\Customer;
use Exception;

class OrderDAO extends BaseDAO
{
	protected static string $tableName = "`order`";

	public static function getAllOrders(): ?array
	{
		try
		{
			$DBResponse = DBSession::requestDB(
				"SELECT * FROM `order`;"
			);

			$orders = [];

			while ($order = mysqli_fetch_assoc($DBResponse))
			{
				$customer = new Customer($order["C_NAME"], $order["C_PHONE"], $order["C_EMAIL"], $order["C_WISH"]);
				$orders[$order["ID"]] = new Order(
					$order["GOOD_ID"],
					$customer,
					$order["ADDRESS"],
					$order["PRICE"],
					$order["ID"],
					$order["DATE_CREATE"],
					$order["STATUS"]
				);
			}

			return $orders;
		}
		catch (Exception $e)
		{
			return null;
		}
	}

	public static function createOrder(Order $orderInfo, Customer $customerInfo): bool
	{
		try
		{
			DBSession::requestDB(
				"INSERT INTO `order` (good_id, date_create, c_name, c_phone, c_email, c_wish, status, address, price)
					VALUE (?, ?, ?, ?, ?, ?, ?, ?, ?);", 'isssssssd', [
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

    public static function updateOrder()
    {
        //
    }

    public static function getOrderByID(int $id) : Order|null
    {
        //
    }
}