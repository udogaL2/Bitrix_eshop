<?php

namespace App\Src\Controller;

use App\Src\DAO\GoodDAO;
use App\Src\DAO\OrderDAO;
use App\Src\DAO\TagDAO;
use App\Src\Model\Good;
use App\Src\Model\Order;
use App\Src\Model\Tag;
use App\Src\Service\AdminService;
use App\Src\Service\InvalidInputException;
use Cassandra\Varint;

class AdminController extends BaseController
{
	public function getMainAdminPageAction(array $errors = []): void
	{
		AuthController::notAdminSessionAction();

		$section = $_GET['section'] ?? 'tags';

		$contentAndField = AdminService::getContentBySection($section);
		$fields = $contentAndField['fields'];
		$content = $contentAndField['values'];
		$allTag = AdminService::allTagAdmin();
		$isOrderSection = $section === 'orders';
		$isGoodSection = $section === 'goods';
		echo self::view('Main/index.html', [
			'content' => self::view('Admin/main.html', [
				'content' => $content,
				'section' => $section,
				'isOrderSection' => $isOrderSection,
				'fields' => $fields,
				'errors' => $errors,
				'allTag' => $allTag,
				'isGoodSection' => $isGoodSection,
			]),
			'isAdmin' => true,
		]);
	}

	public function detailedTagsAdminPageAction($id): void
	{
		if (!is_numeric($id))
		{
			$this->notFoundAction();

			return;
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$tagOld = TagDAO::getTagByID($id);
			if ($tagOld !== null)
			{
				$tag = new Tag($_POST['name'], $id);
				AdminService::updateTag($tag);
			}
		}

		$updatedTag = TagDAO::getTagByID($id);

		if ($updatedTag === null)
		{
			$content = 'something went wrong';
		}
		else
		{
			$field = AdminService::fieldValueTag($updatedTag);
			$content = self::view('Admin/detailed_tags.html', [
				'content' => $field,
			]);
		}

		echo self::view('Main/index.html', [
			'content' => $content,
			'isAdmin' => true,
		]);
	}

	public function detailedGoodAdminPageAction($id): void
	{
		if (!is_numeric($id))
		{
			$this->notFoundAction();

			return;
		}

		AuthController::notAdminSessionAction();

		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$goodOld = GoodDAO::getCurrentGoodById($id, isNotActive: true);
			if ($goodOld !== null)
			{
				// TODO:переделать работу с тегами
				$newTags = $_POST['tags'];

				$updateTime = new \DateTime();
				$good = new Good(
					$_POST['name'],
					$_POST['price'],
					$_POST['article'],
					$_POST['shortDesc'],
					$_POST['fullDesc'],
					$id,
					$updateTime,
					$goodOld->getTimeCreate(),
					$_POST['isActive'],
					$goodOld->getImages(),
					$newTags,
				);
				AdminService::updateGood($good);
			}
		}

		$updatedGood = GoodDAO::getCurrentGoodById($id, isNotActive: true);

		if ($updatedGood === null)
		{
			$content = 'something went wrong';
		}
		else
		{
			$field = AdminService::fieldValueGood($updatedGood);
			$allTag = AdminService::allTagAdmin();
			$tagGood[] = $updatedGood->getTags();
			$tag[] = AdminService::tagGood($tagGood);
			$content = self::view('Admin/detailed_goods.html', [
				'content' => $field,
				'allTag' => $allTag,
				'tagGood' => $tag,
			]);
		}

		echo self::view('Main/index.html', [
			'content' => $content,
			'isAdmin' => true,
		]);
	}

	public function detailedOrdersAdminPageAction($id): void
	{
		if (!is_numeric($id))
		{
			$this->notFoundAction();

			return;
		}

		AuthController::notAdminSessionAction();
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$orderOld = OrderDAO::getOrderByID($id);
			if ($orderOld !== null)
			{
				$order = new Order(
					$orderOld->getGoodId(),
					$orderOld->getCustomer(),
					$orderOld->getAddress(),
					$orderOld->getPrice(),
					$id,
					$orderOld->getDateCreate()->format('YYYY-MM-DD hh:mm:ss'),
					$_POST['status'],
				);
				AdminService::updateOrder($order);
			}
		}

		$updatedOrder = OrderDAO::getOrderByID($id);

		if ($updatedOrder === null)
		{
			$content = 'something went wrong';
		}
		else
		{
			$field = AdminService::fieldValueOrder($updatedOrder);
			$content = self::view('Admin/detailed_orders.html', [
				'content' => $field,
			]);
		}

		echo self::view('Main/index.html', [
			'content' => $content,
			'isAdmin' => true,
		]);
	}

	public function addNewData(): void
	{
		AuthController::notAdminSessionAction();
		$section = $_GET['section'] ?? 'tags';

		if (!isset($_POST['dataInput']))
		{
			ob_clean();
			$this->getMainAdminPageAction(['Fields are empty']);

			return;
		}

		$dataInput = $_POST['dataInput'];
		$tagsInput = $_POST['tagsInput'] ?? [];

		try
		{
			AdminService::addNewDataBySection($section, $dataInput, $tagsInput);
		}
		catch (InvalidInputException $e)
		{
			ob_clean();
			$this->getMainAdminPageAction([$e->getMessage()]);

			return;
		}
	}
}


