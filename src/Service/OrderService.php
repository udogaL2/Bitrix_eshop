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

	public static function registerOrderById(int $id, string $cName, string $cSurname, string $cPhone, string $cEmail): bool
	{
        if (!preg_match('/[A-Z][a-z]{0,126}/', $cName))
        {
            throw new InvalidInputException('invalid name');
        }

        if (!preg_match('/[A-Z][a-z]{0,127}/', $cSurname))
        {
            throw new InvalidInputException('invalid surname');
        }
        if (!preg_match('/\+?\d{11}/', $cPhone))
        {
            throw new InvalidInputException('invalid phone number');
        }
        if (!preg_match('/[a-z\d_\-.]{1,64}@[a-z.]{1,190}/', $cEmail))
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

		$customer = new Customer($cName . ' ' . $cSurname, $cPhone, $cEmail, $c_wish);
		$order = new Order($good->getId(), $customer, $c_address, $good->getPrice());

		return OrderDAO::createOrder($order, $customer);
	}
}