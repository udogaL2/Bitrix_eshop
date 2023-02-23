<?php

namespace App\Src\Controller;

use App\Src\DAO\GoodDAO;
use App\Src\DAO\OrderDAO;
use App\Src\DAO\TagDAO;
use App\Src\Model\Good;
use App\Src\Model\Order;
use App\Src\Model\Tag;
use App\Src\Service\AdminService;

class AdminController extends BaseController
{
	public function getMainAdminPageAction(): void
	{
        AuthController::notAdminSessionAction();

		$section = $_GET['section'] ?? 'tags';

		$content = AdminService::getContentBySection($section);

		if ($content === [])
		{
			$content = AdminService::getContentBySection("tags");
		}

        $isOrderSection = $section === 'orders';

        echo self::view( 'Main/index.html', [
            'content' => self::view('Admin/main.html' , [
                'content' => $content,
                'section' => $section,
                'isOrderSection' => $isOrderSection,
                ]),
            'isAdmin' => true,
        ]);
	}

    public function detailedTagsAdminPageAction(int $id) : void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
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
            $content = self::view('Admin/detailed_goods.html' , [
                'content' => $updatedTag,
            ]);
        }

        echo self::view( 'Main/index.html', [
            'content' => $content,
            'isAdmin' => true,
        ]);

        $content = '';
        echo self::view( 'Main/index.html', [
            'content' => self::view('Admin/detailed_tags.html' , [
                'content' => $content,
            ]),
            'isAdmin' => true,
        ]);
    }

    public function detailedGoodAdminPageAction(int $id) : void
    {
        AuthController::notAdminSessionAction();

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $goodOld = GoodDAO::getCurrentGoodById($id);
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

        $updatedGood = GoodDAO::getCurrentGoodById($id);

        if ($updatedGood === null)
        {
            $content = 'something went wrong';
        }
        else
        {
            $content = self::view('Admin/detailed_goods.html' , [
                'content' => $updatedGood,
            ]);
        }

        echo self::view( 'Main/index.html', [
            'content' => $content,
            'isAdmin' => true,
        ]);
    }

    public function detailedOrdersAdminPageAction(int $id) : void
    {
        AuthController::notAdminSessionAction();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
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
            $content = self::view('Admin/detailed_goods.html' , [
                'content' => $updatedOrder,
            ]);
        }

        echo self::view( 'Main/index.html', [
            'content' => $content,
            'isAdmin' => true,
        ]);
    }
}


