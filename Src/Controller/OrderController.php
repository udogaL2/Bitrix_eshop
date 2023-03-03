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
use App\Src\Service\GoodService;
use App\Src\Service\InvalidInputException;
use App\Src\Service\OrderService;

class OrderController extends BaseController
{
	public function createOrderAction($id, array $errors = []): void
	{
		if (!is_numeric($id))
		{
			$this->notFoundAction();
			return;
		}

		AuthController::adminSessionAction();

		if (!GoodService::isGoodAvailableById($id))
		{
			header("Location: /error/404/");
		}

		$good = OrderService::createOrderById($id);

		echo self::view('Main/index.html', [
			'content' => self::view('Order/orderRegistration.html', [
				'good' => $good,
				'errors' => $errors,
			]),
			'isAdmin' => false,
		]);
	}

	public function registerOrderAction(int $id): void
	{
		$cName = $_POST['cName'];
		$cSurname = $_POST['cSurname'];
		$cPhone = $_POST['cPhone'];
		$cEmail = $_POST['cEmail'];

		try
		{
			$result = OrderService::registerOrderById($id, $cName, $cSurname, $cPhone, $cEmail);
		}
		catch (InvalidInputException $e)
		{
			ob_clean();
			$this->createOrderAction($id, [$e->getMessage()]);

			return;
		}

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
		if (!preg_match('#/order/\d#', $_SERVER['HTTP_REFERER']))
		{
			header('Location: /');
		}
		echo self::view('Main/index.html', [
			'content' => self::view(
				'Order/orderPlaced.html', ['content' => 'Заказ успешно оформлен']
			),
			'isAdmin' => false,
		]);
	}

	function errorOrderAction()
	{
		if (!preg_match('#/order/\d#', $_SERVER['HTTP_REFERER']))
		{
			header('Location: /');
		}
		echo self::view('Main/index.html', [
			'content' => self::view(
				'Order/orderPlaced.html', ['content' => 'Произошла ошибка при создании заказа, повторите попытку']
			),
		]);
	}
}