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
use App\Src\Service\OrderService;

class OrderController extends BaseController
{
	public function createOrderAction(int $id): void
	{
		$good = OrderService::createOrderById($id);

		echo self::view('Main/index.html', [
			'content' => self::view('Order/orderRegistration.html', [
				'good' => $good,
			]),
		]);
	}

	public function registerOrderAction(int $id): void
	{
		$result = OrderService::registerOrderById($id);

		if ($result)
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