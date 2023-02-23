<?php

namespace App\Src\Service;

use App\Src\DAO\UserDAO;

class AuthService
{
    public static function getRoleOrFail(string $login, string $password) : string|false
    {
        $user = UserDAO::checkUser($login);
        if ($user === false)
        {
            return false;
        }
        if (password_verify($password, $user[3]))
        {
            return $user[4];
        }
        return false;
    }
}