<?php

namespace App\Src\DAO;

use App\Core\Database\Service\DBSession;
use Exception;

abstract class BaseDAO
{
	protected static string $tableName = "";

	private const TYPE_EQUALS = [
		"boolean" => 'i',
		"integer" => 'i',
		"double" => 'd',
		"string" => 's',
	];

	public static function deleteUnit(int $unitId): bool
	{
		try
		{
			$tableName = static::$tableName;
			$request = "DELETE FROM {$tableName} WHERE ID = ?";

			DBSession::requestDB($request, 'i', [$unitId]);

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	// В $updateFieldsValue передавать значения в виде ["название столбца, который нужно обновить" => "новое значение", ....]
	public static function updateUnit(int $unitId, array $updateFieldsValue): bool
	{
		try
		{
			if (!$updateFieldsValue)
				return false;

			$tableName = static::$tableName;
			$placeholders = "";
			$rowOfTypes = "";

			foreach ($updateFieldsValue as $field => $value)
			{
				$placeholders .= "`{$field}` = ?,";
				$rowOfTypes .= self::TYPE_EQUALS[gettype($value)];
			}

			$placeholders = rtrim($placeholders, ',');

			$request = "UPDATE {$tableName} SET {$placeholders} WHERE ID = ?;";
			$par = array_merge(array_values($updateFieldsValue), [$unitId]);
			$rowOfTypes .= 'i';

			DBSession::requestDB(
				$request,
				$rowOfTypes,
				$par
			);

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}
}