<?php

namespace App\Src\Controller;

class OrderController extends BaseController
{
    public function viewOrderPageAction() : void
    {
        echo self::view('Main/index.html', [
            'content' => self::view('Order/orderRegistration.html', []),
        ]);
    }

    public function createNewOrder()
    {

    }
}