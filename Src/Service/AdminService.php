<?php

namespace App\Src\Service;

use App\Config\Config;
use App\Src\DAO\GoodDAO;
use App\Src\DAO\OrderDAO;
use App\Src\DAO\TagDAO;
use App\Src\Model\Good;
use App\Src\Model\Order;
use App\Src\Model\Tag;

class AdminService
{
	public static function getContentBySection(string $section)
	{
		if ($section === 'tags')
		{
			return [
				'fields' => ['name'],
				'values' => TagDAO::getAllTags(),
			];
		}

		if ($section === 'goods')
		{
			return [
				'fields' => ['name', 'price', 'short description', 'full description'],
				'values' => GoodDAO::getAllGoods(),
			];
		}

		if ($section === 'orders')
		{
			$orders = OrderDAO::getAllOrders();
			$goodsIDNameAndStatus = [];
			foreach ($orders as $order)
			{
				$good = GoodDAO::getCurrentGoodById($order->getGoodId());
				if ($good !== null)
				{
					$goodsIDNameAndStatus[] = [
						'ID' => $order->getId(),
						'goodName' => $good->getName(),
						'status' => $order->getStatus(),
					];
				}
			}

			return [
				'fields' => [],
				'values' => $goodsIDNameAndStatus,
			];
		}

		return [];
	}

	public static function addNewDataBySection(string $section, array $dataInput, array $tagsInput): void
	{
		foreach ($dataInput as $item)
		{
			if ($item === '')
			{
				throw new InvalidInputException('Some fields are empty');
			}
		}

		if ($section === 'tags')
		{
			$tagName = new Tag(HtmlService::safe($dataInput[0]));
			TagDAO::createNewTag($tagName);
			header("Location: /admin");
		}

		if ($section === 'goods')
		{
			$name = HtmlService::safe($dataInput[0]);
			$price = HtmlService::safe($dataInput[1]);
			$shortDesc = HtmlService::safe($dataInput[2]);
			$fullDesc = HtmlService::safe($dataInput[3]);

			if (mb_strlen($shortDesc) < 2 || mb_strlen($fullDesc) < 2)
			{
				throw new InvalidInputException("Description string length is too short");
			}
			if (mb_strlen($shortDesc) > 2500 || mb_strlen($fullDesc) > 5000)
			{
				throw new InvalidInputException("Description string length is too long");
			}

			if (!is_numeric($price))
			{
				throw new InvalidInputException('Price is not numeric');
			}

			$tags = [];
			foreach ($tagsInput as $key => $value)
			{
				$tags[] = $key;
			}

			if (!$tags)
			{
				throw new InvalidInputException('Tags were not selected');
			}

			$article = HtmlService::safe(GoodService::generateGoodCode(TagDAO::getTagByID($tags[0])->getName(), $name));

			$good = new Good($name, $price, $article, $shortDesc, $fullDesc);
			$goodId = GoodDAO::createGood($good);
			TagDAO::createLinks($goodId, $tags);
			header("Location: /admin");
		}
	}

	public static function updateTag(Tag $tag)
	{
		//TagDAO::updateTag();
	}

	public static function updateGood(Good $good)
	{
		//TagDAO::updateGood();
	}

	public static function updateOrder(Order $order)
	{
		//TagDAO::updateGood();
	}

	public static function fieldValueGood(Good $good): array
	{
		$tag = [];
		if (is_array($good->getTags()))
		{
			foreach ($good->getTags() as $item)
			{
				$tag[] = $item->getName();
			}
		}

		$field[] = [
			//             'Id товара' => $good -> getId(),
			'Наименование товара' => $good->getName(),
			'Цена товара' => $good->getPrice(),
			'Короткое описание' => $good->getShortDesc(),
			'Полное описание' => $good->getFullDesc(),
		];

		return $field;
	}

	public static function allTagAdmin(): array
	{
		$tag = [];
		$allTag = TagDAO::getAllTags();
		foreach ($allTag as $item)
		{
			$tag[] = $item->getName();
		}

		return $tag;
	}

	public static function isCheckedTag(string $tag, array $tagGood): string
	{
		foreach ($tagGood as $item)
		{
			if (in_array($tag, $item, true))
			{
				return 'checked';
			}
		}

		return '';
	}

	public static function tagGood(array $tag): array
	{
		$tagGood = [];
		foreach ($tag as $item)
		{
			foreach ($item as $key => $value)
			{
				$tagGood[] = $value->getName();
			}

		}

		return $tagGood;
	}

	public static function fieldValueOrder(Order $order): array
	{
		$field[] = [
			//                'Id заказа' => $order -> getId(),
			'Id товара' => $order->getGoodId(),
			'Имя покупателя' => $order->getCustomer()->getName(),
			'Статус заказа' => $order->getStatus(),
			'Адрес заказа' => $order->getAddress(),
			'Стоимость заказа' => $order->getPrice(),
		];

		return $field;
	}

	public static function fieldValueTag(Tag $tag): array
	{
		$field[] = [
			//                'Id тега' => $tag -> getId(),
			'Название тега' => $tag->getName(),
		];

		return $field;
	}
}