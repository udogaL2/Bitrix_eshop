<?php

namespace App\Src\Controller;

use App\Src\Model\Good;

class IndexController extends BaseController
{
    public function indexAction()
    {
        // example of goods
        $goods = [
            new Good("product1", 56, "01-01-1970"),
            new Good("product2", 57, "01-01-1970"),
        ];
        //

        try
        {
            echo $this->view('Main/index.html', [
                'content' => $this->view('Good/good.html', [
                    'goods' => $goods,
                ]),
            ]);
        }
        catch (PathException $e)
        {
            //Logger::addError($e->getMessage());
            $this->notFoundAction();
        }
    }
}
