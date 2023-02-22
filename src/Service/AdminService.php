<?php

namespace App\Src\Service;

use App\Config\Config;
use App\Src\DAO\GoodDAO;
use App\Src\DAO\OrderDAO;
use App\Src\DAO\TagDAO;

class AdminService
{
    public static function checkLoginAndPassword($login, $password) : bool
    {
        return true;
    }

	public static function getContentBySection(string $section)
	{
		if ($section === 'tags')
		{
			return TagDAO::getAllTags();
		}

		if ($section === 'goods')
		{
			return GoodDAO::getAllGoods();
		}

		if ($section === 'orders')
		{
			//return OrderDAO::
		}


		return [];
	}
}