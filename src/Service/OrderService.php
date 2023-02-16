<?php

namespace App\Src\Service;

use App\Src\DAO\GoodDAO;
use App\Src\DAO\OrderDAO;
use App\Src\Model\Customer;
use App\Src\Model\Good;
use App\Src\Model\Order;

class OrderService
{
	public static function createOrderById(int $id): Good
	{
		return GoodDAO::getCurrentGoodById($id, isWithTags: false);
	}

	public static function registerOrderById(int $id): bool
	{
		$c_name = $_POST['c_name'] . ' ' . $_POST['c_surname'];
		$c_phone = $_POST['c_phone'];
		$c_email = $_POST['c_email'];
		$c_wish = "Some wish";
		$c_address = "Some address";
		$good = GoodDAO::getCurrentGoodById($id, isWithImages: false, isWithTags: false);

		if ($good === null)
		{
			return false;
		}

		$customer = new Customer($c_name, $c_phone, $c_email, $c_wish);
		$order = new Order($good->getId(), $customer, $c_address, $good->getPrice());

		return OrderDAO::createOrder($order, $customer);
	}
}