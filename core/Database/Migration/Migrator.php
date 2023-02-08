<?php

namespace App\Core\Database\Migration;

use App\Core\Database\Service\DB_session;
use App\Config\Config;
use Exception;

class Migrator
{
	// todo(что-то сделать с этими путями до файлов)
	protected static string $path_to_migration_folder = Config::ROOT . "/src/Migration/";

	public static function migrate(): void
	{
		/* todo()
		 * 	+ 1. просмотреть последнюю запись о миграции
		 *  	+ 1.1. если не имеется начать с начала
		 * 		+ 1.2. если имеется начинать со следующей
		 * 	+ 2. пройтись по /src/Migration/ и найти все новые миграции
		 * 	+ 3. выполнить все миграции
		 * 	+ 4. обновить данные о последней примененной миграции в таблице migration
		 */

		// 1.
		try
		{
			$last_migration_or_bool = DB_session::request_db(
				"select NAME from migration order by ID desc limit 1"
			);
            $last_migration = mysqli_fetch_row($last_migration_or_bool);
            if (!is_array($last_migration))
            {
                throw new Exception('cannot get info about last migration');
            }
			$last_migration = count($last_migration) > 0 ? $last_migration[0] : null;
		}
		catch (Exception $e)
		{
			$last_migration = null;
		}

		// 2.
		$all_migrations = array_diff(scandir(self::$path_to_migration_folder), ['..', '.']);

		if (!$all_migrations)
		{
			return;
		}

		$unfulfilled_migrations = $last_migration !== null ? array_slice(
			$all_migrations,
			array_search($last_migration, $all_migrations) - 1
		) : $all_migrations;

		// 3.
		if (!$unfulfilled_migrations)
		{
			return;
		}

		// данная часть кода выполняется только если есть невыполненные миграции
		foreach ($unfulfilled_migrations as $unfulfilled_migration)
		{
			$sql_request = file_get_contents(self::$path_to_migration_folder . $unfulfilled_migration);
			$request_res = DB_session::request_db($sql_request, substr_count($sql_request, ";") > 1);

			if (!$request_res)
			{
				throw new Exception("миграция " . $unfulfilled_migration . " не выполнена");
			}
		}

		// 4.
		$last_migration = array_pop($unfulfilled_migrations);
		DB_session::request_db("insert into migration (NAME) value (?);", 's', [$last_migration]);
	}
}
