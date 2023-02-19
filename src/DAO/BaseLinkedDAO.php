<?php

namespace App\Src\DAO;

use App\Core\Database\Service\DBSession;
use Exception;

// TODO(написать комментарии с описанием работы)
abstract class BaseLinkedDAO extends BaseDAO
{
	protected static string $linkTableName = "";
	protected static string $primaryLinkColumn = "";
	protected static string $secondaryLinkColumn = "";

	/*
	 *	Создание связей необходимо делать после создания соответствующих записей в первичной и вторичной таблицах
	 *	(т.е. необходимо существование передаваемых id)
	 *	Примечание: в нашем случае первичными таблицами является good, а вторичными tag и image
	 *
	 *	Пример: для товара с id = 1 нужно создать связи с тегами ids = [1, 2, 3, 4] =>
	 *	необходимо вызвать TagDAO::createLinks($goodId, $tagIds);
	 *	Пример: для товара с id = 1 нужно создать связи с изображениями ids = [1, 2, 3, 4] =>
	 *	необходимо вызвать ImageDAO::createLinks($goodId, $imageIds);
	 *
	 *	Поле $isSwitchLinkColumn меняет местами первичную и вторичную таблицы, необходимо для шаблона, на данный момент
	 *	нет необходимости в использовании.
	 */
	public static function createLinks(
		int   $primaryUnitId,
		array $secondaryUnitIds,
		bool  $isSwitchLinkColumn = false
	): bool
	{
		try
		{
			$placeholders = str_repeat("({$primaryUnitId}, ?),", count($secondaryUnitIds) - 1)
				. "({$primaryUnitId}, ?)";
			$linkTableName = static::$linkTableName;
			$primaryLinkColumn = static::$primaryLinkColumn;
			$secondaryLinkColumn = static::$secondaryLinkColumn;

			$columnOrder = $isSwitchLinkColumn ? "($secondaryLinkColumn, $primaryLinkColumn)"
				: "($primaryLinkColumn, $secondaryLinkColumn)";

			$request = "INSERT INTO {$linkTableName} {$columnOrder} values {$placeholders};";

			DBSession::requestDB($request, str_repeat('i', count($secondaryUnitIds)), $secondaryUnitIds);

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	/*
	 * При удалении из первичной таблицы, например товара, нужно сначала удалить связи,
	 * а потом сам товар или заказ.
	 * Для удаления таких связей необходимо вызвать TagDAO::deleteLinks(mainUnitId: $goodId)
	 * и ImageDAO::deleteLinks(mainUnitId: $goodId)
	 *
	 * При удалении из вторичной таблицы, например тега или изображения, нужно также сначала удалить связи,
	 * а потом сам объект.
	 * Для удаления необходимо вызвать TagDAO::deleteLinks(secondaryUnitIds: $tagIds)
	 *
	 * При точечном удалении связи для конкретного товара необходимо передать оба параметра:
	 * TagDAO::deleteLinks($goodId, $tagIds)
	 */
	public static function deleteLinks(
		int $mainUnitId = null,
		array $secondaryUnitIds = []
	): bool
	{
		try
		{
			if (isset($mainUnitId) && isset($secondaryUnitIds))
			{
				$primaryLinkColumn = static::$primaryLinkColumn;
				$secondaryLinkColumn = static::$secondaryLinkColumn;

				$condition = "{$primaryLinkColumn} = ? and {$secondaryLinkColumn} in (" .
					str_repeat('?,', count($secondaryUnitIds) - 1) . '?)';
				$rowOfValue = 'i' . str_repeat('i', count($secondaryUnitIds));
				$value = array_merge([$mainUnitId], $secondaryUnitIds);
			}
			else
			{
				$column = isset($mainUnitId) ? static::$primaryLinkColumn : static::$secondaryLinkColumn;

				$condition = isset($mainUnitId) ? "{$column} = ?" : "{$column} in ("  .
					str_repeat('?,', count($secondaryUnitIds) - 1) . '?)';

				$rowOfValue = isset($mainUnitId) ? 'i' : str_repeat('i', count($secondaryUnitIds));

				$value = [$mainUnitId] ?? $secondaryUnitIds;
			}

			$linkTableName = static::$linkTableName;
			$query = "DELETE FROM {$linkTableName} WHERE {$condition}";

			DBSession::requestDB($query, $rowOfValue, $value);

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}
}