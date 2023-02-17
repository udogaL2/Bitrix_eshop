<?php

namespace App\Src\Service;

use App\Src\DAO\GoodDAO;
use App\Src\DAO\OrderDAO;
use App\Src\Model\Customer;
use App\Src\Model\Good;
use App\Src\Model\Order;
use http\Exception\InvalidArgumentException;

class OrderService
{
	public static function createOrderById(int $id): Good
	{
		return GoodDAO::getCurrentGoodById($id, isWithTags: false);
	}

	public static function registerOrderById(int $id): bool
	{
        if (!preg_match('/[A-Z][a-z]{0,126}/', $_POST['c_name']))
        {
            throw new InvalidInputException('invalid name');
        }

        if (!preg_match('/[A-Z][a-z]{0,127}/', $_POST['c_surname']))
        {
            throw new InvalidInputException('invalid surname');
        }
        if (!preg_match('/\+?\d{11}/', $_POST['c_phone']))
        {
            throw new InvalidInputException('invalid phone number');
        }
        if (!preg_match('/[a-z\d_\-.]{1,64}@[a-z.]{1,190}/', $_POST['c_email']))
        {
            throw new InvalidInputException('invalid e-mail');
        }
		$c_wish = "Some wish";
		$c_address = "Some address";
		$good = GoodDAO::getCurrentGoodById($id, isWithImages: false, isWithTags: false);

		if ($good === null)
		{
			return false;
		}

		$customer = new Customer($_POST['c_name'] . $_POST['c_surname'], $_POST['c_phone'],
            $_POST['c_email'], $c_wish);
		$order = new Order($good->getId(), $customer, $c_address, $good->getPrice());

		return OrderDAO::createOrder($order, $customer);
	}
}