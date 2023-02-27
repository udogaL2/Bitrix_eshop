<?php

/*
 * Для запроса к БД необходимо вызвать метод request_to_db
 * query - сам запрос
 * row_of_types - строка, содержащая типы для связанных переменных
 * vars - сами переменные, передавать необходимо внутри массива.
 * is_multi_query - флаг для множественного запроса (используется для мигратора, в других местах не использовать)
 *
 * Чтобы сделать обычный запрос, нужно просто передать строку с запросом:
 * request_db("select * from table;")
 *
 * Для запроса со связанными переменными необходимо также передать все оставшиеся параметры
 * request_db("select * from table where id = ?;", "i", [$id])
 *
 * Для множественного запроса необходимо поставить флаг is_multi_query
 * request_db("select * from table1; select * from table2;", is_multi_query: true)
 * !ВАЖНО! данный вариант запроса необходим для мигратора, использовать его в иных местах не нужно
 *
 * Метод возвращает mysqli_result, если внутри запроса был select
 * В противном случае возвращается bool, как итог выполнения запроса
 */

namespace App\Core\Database\Service;

use App\Core\Routing\Router;
use Exception;
use mysqli;
use App\Config\Config;

class DBSession
{
	private static ?mysqli $connection = null;

	private static function createDBConnection(): void
	{
		if (self::$connection !== null)
		{
			return;
		}

		$db_host = Config::DB_HOST;
		$username = Config::DB_USERNAME;
		$password = Config::DB_PASSWORD;
		$db_name = Config::DB_NAME;

		self::$connection = mysqli_init();

		$connection_result = mysqli_real_connect(
			self::$connection,
			$db_host,
			$username,
			$password,
			$db_name
		);

		if (!$connection_result)
		{
			$error = mysqli_connect_errno() . ': ' . mysqli_connect_error();
			throw new Exception($error);
		}

		$encoding_result = mysqli_set_charset(self::$connection, 'utf8');

		if (!$encoding_result)
		{
			throw new Exception(mysqli_error(self::$connection));
		}

	}

	public static function requestDB(
		string $query,
		string $rowOfTypes = '',
		array  $vars = [],
		bool   $isMultiQuery = false
	): \mysqli_result|bool
	{
		if (self::$connection === null)
		{
			try
			{
				self::createDBConnection();
			}
			catch (Exception $e)
			{
				(new Router())->fatalError();
			}

		}

		if ($rowOfTypes)
		{
			// если передается список типов, то запрос формируется с помощью связанных переменных, иначе с обычным
			$statement = mysqli_prepare(self::$connection, $query);

			if (is_bool($statement))
			{
				throw new Exception(mysqli_error(self::$connection));
			}

			mysqli_stmt_bind_param($statement, $rowOfTypes, ...$vars);
			$execute_result = mysqli_stmt_execute($statement);

			if (!$execute_result)
			{
				throw new Exception(mysqli_error(self::$connection));
			}

			$result = mysqli_stmt_get_result($statement);
		}
		else
		{
			if (!$isMultiQuery)
			{
				$result = mysqli_query(self::$connection, $query);
			}
			else
			{
				$result = mysqli_multi_query(self::$connection, $query);
				self::clearBuffer();
			}
		}

		return $result;
	}

	private static function clearBuffer(): void
	{
		while (self::$connection->next_result())
		{
			self::$connection->store_result();
		}
	}
}
