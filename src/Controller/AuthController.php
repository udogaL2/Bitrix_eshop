<?php

namespace App\Src\Controller;

use App\Src\Service\AuthService;

class AuthController extends BaseController
{
    private string $error = '';
    public function loginAction() : void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $role = AuthService::getRoleOrFail($_POST['login'], $_POST['password']);
            var_dump($role);
            if ($role === false)
            {
                $this->error = 'invalid login or password';
            }
            if ($role === 'admin')
            {
                session_start();
                $_SESSION['user'] = 'admin';
                session_write_close();
                header('Location: /admin');
            }
        }

        echo self::view('Auth/auth.html', [
                'error' => $this->error,
        ]);
    }

    public function logoutAction() : void
    {
        session_start();
        if (isset($_SESSION))
        {
            session_destroy();
        }
        header('Location: /');
    }

    public static function adminSessionAction() : void
    {
        session_start();
        if (isset($_SESSION['user']))
        {
            if ($_SESSION['user'] === 'admin')
            {
                header('Location: /admin');
            }
        }
    }
    public static function notAdminSessionAction() : void
    {
        session_start();
        if ($_SESSION['user'] !== 'admin')
        {
            header('Location: /');
        }
    }
}
