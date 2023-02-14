<?php

namespace App\Core\Database\Migration;

use App\Core\Database\Service\DB_session;
use App\Config\Config;
use Exception;

class Migrator
{
	protected static string $pathToMigrationFolder = Config::ROOT . "/src/Migration/";

	public static function migrate(): void
	{
		// 1. последняя запись о миграции
		try
		{
			$lastMigrationOrBool = DB_session::request_db(
				"select NAME from migration order by ID desc limit 1"
			);
			$lastMigration = $lastMigrationOrBool ? mysqli_fetch_row($lastMigrationOrBool)[0] : null;
			if (!$lastMigration)
			{
				throw new Exception('cannot get info about last migration');
			}
		}
		catch (Exception $e)
		{
			$lastMigration = null;
		}

		// 2. поиск всех новых миграций в /src/Migration/
		$allMigrations = array_diff(scandir(self::$pathToMigrationFolder), ['..', '.']);

		if (!$allMigrations)
		{
			return;
		}

		$unfulfilledMigrations = $lastMigration !== null ? array_slice(
			$allMigrations,
			array_search($lastMigration, $allMigrations) - 1
		) : $allMigrations;

		// 3. выполнить все миграции
		if (!$unfulfilledMigrations)
		{
			return;
		}

		// данная часть кода выполняется только если есть невыполненные миграции
		foreach ($unfulfilledMigrations as $unfulfilledMigration)
		{
			$sqlRequest = file_get_contents(self::$pathToMigrationFolder . $unfulfilledMigration);
			$requestResult = DB_session::request_db($sqlRequest, is_multi_query: substr_count($sqlRequest, ";") > 1);

			if (!$requestResult)
			{
				throw new Exception("миграция " . $unfulfilledMigration . " не выполнена");
			}
		}

		// 4. обновление данных о последней примененной миграции в таблице migration
		$lastMigration = array_pop($unfulfilledMigrations);
		DB_session::request_db("insert into migration (NAME) value (?);", 's', [$lastMigration]);
	}
}
