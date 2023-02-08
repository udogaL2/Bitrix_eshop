<?php

namespace App\Src\Controller;

class OrderController extends BaseController
{
    public function viewOrderPageAction() : void
    {
        echo $this->view('Main/index.html', [
            'content' => $this->view('Order/orderRegistration.html', []),
        ]);
    }

    public function createNewOrder()
    {

    }
}