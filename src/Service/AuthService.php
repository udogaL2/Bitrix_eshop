<?php

namespace App\Src\Service;

use App\Src\DAO\UserDAO;

class AuthService
{
    public static function getRoleOrFail(string $login, string $password) : string|false
    {
        $user = UserDAO::getUser($login);

        if ($user === false)
        {
            return false;
        }
        if (password_verify($password, $user->getHashedPassword()))
        {
            return $user->getRole();
        }
        return false;
    }
}