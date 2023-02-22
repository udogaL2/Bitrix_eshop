<?php

namespace App\Src\Controller;

use App\Src\Service\AdminService;

class AuthController extends BaseController
{
    private string $error = '';
    public function authPageAction() : void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $valid = AdminService::checkLoginAndPassword($_POST['login'], $_POST['password']);
            if ($valid)
            {
                session_start();
                header('Location: /admin');
            }
            $this->error = 'invalid login or password';
        }

        echo self::view('Main/index.html', [
            'content' => self::view('Admin/auth.html', [
                'error' => $this->error,
            ])
        ]);
    }
}
