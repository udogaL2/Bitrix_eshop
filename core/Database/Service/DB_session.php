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

// перенести в потом index.php
use Exception;
use mysqli;

require_once "src\Services\configuration_service.php";

class DB_session
{
	private static ?mysqli $connection = null;

	private static function create_db_connection(): void
	{
		if (self::$connection !== null)
		{
			return;
		}

		$db_host = option('DB_HOST');
		$username = option('DB_USERNAME');
		$password = option('DB_PASSWORD');
		$db_name = option('DB_NAME');

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

	public static function request_db(
		string $query,
		string $row_of_types = '',
		array  $vars = [],
		bool   $is_multi_query = false,
	): \mysqli_result|bool
	{
		if (self::$connection === null)
		{
			self::create_db_connection();
		}

		if ($row_of_types)
		{
			// если передается список типов, то запрос формируется с помощью связанных переменных, иначе с обычным
			$statement = mysqli_prepare(self::$connection, $query);

			mysqli_stmt_bind_param($statement, $row_of_types, ...$vars);
			$execute_result = mysqli_stmt_execute($statement);

			if (!$execute_result)
			{
				throw new Exception(mysqli_error(self::$connection));
			}

			$result = mysqli_stmt_get_result($statement);
		}
		else
		{
			if (!$is_multi_query)
			{
				$result = mysqli_query(self::$connection, $query);
			}
			else
			{
				$result = mysqli_multi_query(self::$connection, $query);
				self::clear_buffer();
			}
		}

		return $result;
	}

	public static function clear_buffer(): void
	{
		while (self::$connection->next_result())
		{
			self::$connection->store_result();
		}
	}
}