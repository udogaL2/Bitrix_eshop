<?php

namespace App\Src\Controller;

use App\Core\Database\Service\DBSession;
use App\Src\Model\Order;
use App\Src\Model\Customer;
use App\Src\Model\Good;
use App\Src\Model\Image;
use App\Src\Model\Tag;
use Exception;

class OrderController extends BaseController
{
	function createOrderAction(int $id)
	{
		$good = $this->getGoodById($id);

		echo self::view('Main/index.html', [
			'content' => self::view('Order/orderRegistration.html', [
				'good' => $good,
			])
		]);
	}

	function registerOrderAction(int $id)
	{
		$c_name = $_POST['c_name'] . ' ' . $_POST['c_surname'];
		$c_phone = $_POST['c_phone'];
		$c_email = $_POST['c_email'];
		$c_wish = "Some wish";
		$c_address = "Some address";
		$customer = new Customer($c_name, $c_phone, $c_email, $c_wish);
		$good = $this->getGoodById($id);
		$order = new Order($good->getId(), $customer, $c_address, $good->getPrice());

		//insert in order table
		//TODO(привести в порядок)
		try{
			DBSession::requestDB(
				"INSERT INTO `order` (good_id, date_create, c_name, c_phone, c_email, c_wish, status, address, price)
					VALUE (?, ?, ?, ?, ?, ?, ?, ?, ?);",
					'isssssssd',
					[$good->getId(), date('Y-m-d H:i:s'), $c_name, $c_phone,
					$c_email, $c_wish, 'new', $c_address, $good->getPrice()]);
		}
		catch (Exception $e)
		{
			$this->notFoundAction();
			return;
		}

		echo self::view('Main/index.html', [
			'content' => self::view('Order/orderPlaced.html')
		]);
	}

	function getGoodById(int $id): ?Good
	{
		try
		{
			$good_request = DBSession::requestDB(
				'SELECT * FROM good where ID = ?;',
				'i',
				[$id]
			);
		}
		catch (\Exception $e)
		{
			$this->notFoundAction();
			return null;
		}

		$good_result = mysqli_fetch_assoc($good_request);

		try
		{
			$tags_request = DBSession::requestDB(
				'SELECT ID, NAME FROM tag t
            INNER JOIN good_tag gt on t.ID = gt.TAG_ID AND GOOD_ID = ?;',
				'i',
				[$id]
			);

			$tags = [];
			if ($tags_request !== null)
			{
				if (mysqli_num_rows($tags_request) !== 0)
				{
					while ($tag = mysqli_fetch_assoc($tags_request))
					{
						$tags[] = new Tag($tag['NAME'], $tag['ID']);
					}
				}
			}
		}
		catch (\Exception $e)
		{
			//Logger::addError($e)
			$tags = [];
		}

		try
		{
			$images_request = DBSession::requestDB(
				'SELECT ID, PATH, HEIGHT, WIDTH, IS_MAIN FROM image img
            INNER JOIN good_image g_img on img.ID = g_img.IMAGE_ID AND GOOD_ID = ?;',
				'i',
				[$id]
			);

			$images = [];
			if ($images_request !== null)
			{
				if (mysqli_num_rows($images_request) !== 0)
				{
					while ($img = mysqli_fetch_assoc($images_request))
					{
						$images[] = new Image(
							$img["PATH"],
							$img["WIDTH"],
							$img["HEIGHT"],
							$img["IS_MAIN"],
							$img["ID"]
						);
					}
				}
			}
		}
		catch (\Exception $e)
		{
			//Logger::addError($e)
			$images = [];
		}

		return new Good(
			$good_result['NAME'],
			$good_result['PRICE'],
			$good_result['GOOD_CODE'],
			$good_result['SHORT_DESC'],
			$good_result['FULL_DESC'],
			$good_result['ID'],
			new \DateTime($good_result['DATE_UPDATE']),
			new \DateTime($good_result['DATE_CREATE']),
			$good_result['IS_ACTIVE'],
			$images,
			$tags
		);
	}
}