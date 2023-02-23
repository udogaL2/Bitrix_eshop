<?php

namespace App\Src\DAO;

use App\Core\Database\Service\DBSession;
use App\Src\Model\User;
use Exception;

class UserDAO
{
	public static function getUser(string $login): User|bool
	{
		try
		{
			$DBResponse = DBSession::requestDB("select * from user where LOGIN = ?", 's', [$login]);

			$result = mysqli_fetch_assoc($DBResponse);

			return $result ? new User(
				$result["ID"], $result["LOGIN"], $result["EMAIL"], $result["PASSWORD"], $result["ROLE"]
			) : false;

		}
		catch (Exception $e)
		{
			return false;
		}
	}
}
