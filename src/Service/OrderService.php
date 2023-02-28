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
		$cName = HtmlService::safe($cName);
		$cSurname = HtmlService::safe($cSurname);
		$cPhone = filter_var($cPhone, FILTER_SANITIZE_NUMBER_INT);
		$cEmail = filter_var($cEmail, FILTER_VALIDATE_EMAIL);
		$cName = HtmlService::safe($cName);
		$cSurname = HtmlService::safe($cSurname);

        if (!$cPhone)
        {
            throw new InvalidInputException('invalid phone number');
        }
        if (!$cEmail)
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