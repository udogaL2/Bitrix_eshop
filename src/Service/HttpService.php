<?php

namespace App\Src\Service;

class HttpService
{
	public static function validateUserInput($input): string
	{
		$input = trim($input);
		$input = stripslashes($input);
		return htmlspecialchars($input);
	}
}