<?php

namespace App\Src\Controller;

use App\Core\Database\Service\DBSession;
use App\Src\DAO\GoodDAO;
use App\Src\DAO\OrderDAO;
use App\Src\Model\Order;
use App\Src\Model\Customer;
use App\Src\Model\Good;
use App\Src\Model\Image;
use App\Src\Model\Tag;

class OrderController extends BaseController
{
	function createOrderAction(int $id)
	{
		$good = GoodDAO::getCurrentGoodById($id, isWithTags: false);

		echo self::view('Main/index.html', [
			'content' => self::view('Order/orderRegistration.html', [
				'good' => $good,
			]),
		]);
	}

	function registerOrderAction(int $id)
	{
		$c_name = $_POST['c_name'] . ' ' . $_POST['c_surname'];
		$c_phone = $_POST['c_phone'];
		$c_email = $_POST['c_email'];
		$c_wish = "Some wish";
		$c_address = "Some address";
		$good = GoodDAO::getCurrentGoodById($id, isWithImages: false, isWithTags: false);

		$customer = new Customer($c_name, $c_phone, $c_email, $c_wish);
		$order = new Order($good->getId(), $customer, $c_address, $good->getPrice());

		$res = OrderDAO::createOrder($order, $customer);

		if ($res)
		{
			header("Location: /orderPlaced/");
		}
		else
		{
			header("Location: /orderError/");
		}
	}

	// TODO(переделать в общий метод, который будет обрабатывать системное сообщение для страницы)
	// Пример: /message/success или /message/order_error
	//
	function successOrderAction()
	{
		echo self::view('Main/index.html', [
			'content' => self::view(
				'Order/orderPlaced.html',
				['content' => 'Заказ успешно оформлен']
			),
		]);
	}

	function errorOrderAction()
	{
		echo self::view('Main/index.html', [
			'content' => self::view(
				'Order/orderPlaced.html',
				['content' => 'Произошла ошибка при создании заказа, повторите попытку']
			),
		]);
	}
}