<?php

namespace App\Src\DAO;

use App\Core\Database\Service\DBSession;

class UserDAO
{
	public static function checkUser(string $login) : array|bool
	{
		$DBResponse = DBSession::requestDB("select * from user where LOGIN = ?", 's', [$login]);

		$result = mysqli_fetch_all($DBResponse);

		return $result ? $result[0] : false;
	}
}