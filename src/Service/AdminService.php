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
			return [ 'fields' => ['name'],
					 'values' => TagDAO::getAllTags(),
			];
		}

		if ($section === 'goods')
		{
			return [ 'fields' => ['name', 'price', 'article', 'tags'],
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
            return [ 'fields' => [],
					 'values' => $goodsIDNameAndStatus,
			];
		}

		return [];
	}

	public static function addNewDataBySection(string $section, array $dataInput): void
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
			$article = HtmlService::safe($dataInput[2]);
			$tags = HtmlService::safe($dataInput[3]);
			$tags = explode(',', $tags);

			$good = new Good($name, $price, $article);
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

    public static function fieldValueGood(Good $good)
    {
        $tag ='';
        if(is_array($good -> getTags()))
        {
            foreach($good -> getTags() as $item)
            {
                $tag="{$tag} {$item -> getName()},";
            }
        }
        else
        {
            $tag = $good -> getTags() -> getName();
        }


        $field[]=
            [
             'Id товара' => $good -> getId(),
             'Наименование товара' => $good -> getName(),
             'Цена товара' => $good -> getPrice(),
             'Короткое описание' => $good -> getShortDesc(),
             'Полное описание' => $good -> getFullDesc(),
             'Теги товара' => $tag,
            ];

            return $field;
    }

    public static function fieldValueOrder(Order $order)
    {
        $field[]=
            [
                'Id заказа' => $order -> getId(),
                'Id товара' => $order -> getGoodId(),
                'Имя покупателя' => $order -> getCustomer() ->getName(),
                'Статус заказа' => $order -> getStatus(),
                'Адрес заказа' => $order -> getAddress(),
                'Стоимость заказа' => $order -> getPrice(),
            ];
        return $field;
    }

    public static function fieldValueTag(Tag $tag)
    {
        $field[]=
            [
                'Id тега' => $tag -> getId(),
                'Название тега' => $tag -> getName(),
            ];

        return $field;
    }
}