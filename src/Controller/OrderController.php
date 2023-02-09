<?php

namespace App\Src\Controller;

use App\Src\Model\Good;

class OrderController extends BaseController
{
	function createOrderAction(int $id)
	{
		//find good by id затем передать его на вью
		$good = new Good("product1", $id, "01-01-1970");

		echo self::view('Main/index.html', [
			'content' => self::view('Order/orderRegistration.html', [
				'good' => $good,
			])
		]);
	}
}