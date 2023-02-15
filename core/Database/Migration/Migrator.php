<?php

namespace App\Core\Database\Migration;

use App\Core\Database\Service\DBSession;
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
			$lastMigrationOrBool = DBSession::requestDB(
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
			$requestResult = DBSession::requestDB($sqlRequest, isMultiQuery: substr_count($sqlRequest, ";") > 1);

			if (!$requestResult)
			{
				throw new Exception("миграция " . $unfulfilledMigration . " не выполнена");
			}
		}

		// 4. обновление данных о последней примененной миграции в таблице migration
		$lastMigration = array_pop($unfulfilledMigrations);
		DBSession::requestDB("insert into migration (NAME) value (?);", 's', [$lastMigration]);
	}
}
