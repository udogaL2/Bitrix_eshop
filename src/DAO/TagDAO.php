<?php

namespace App\Src\DAO;

use App\Core\Database\Service\DBSession;
use App\Src\Model\Tag;
use Exception;

class TagDAO
{
	/**
	 * @return Tag[]|null
	 */
	public static function getTagsOfGoods(array $preparedGoodsIds): ?array
	{
		try
		{
			$placeholders = str_repeat('?,', count($preparedGoodsIds) - 1) . '?';

			$query = "select gt.GOOD_ID, (select t.NAME from tag t where gt.TAG_ID = t.ID) as tag
					from good_tag gt
					where gt.GOOD_ID in ({$placeholders});";

			$DBResponse = DBSession::requestDB($query, str_repeat('i', count($preparedGoodsIds)), $preparedGoodsIds);

			$goodIdTag = [];

			while ($tag = mysqli_fetch_row($DBResponse))
			{
				$goodIdTag[$tag[0]][] = new Tag($tag[1], null);
			}

			return $goodIdTag;

		}
		catch (Exception $e)
		{
			return null;
		}
	}

	public static function createNewTag(Tag $tag): bool
	{
		try
		{
			DBSession::requestDB(
				"insert into tag (name)
				VALUE (?)", "s", [$tag->getName()]
			);

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	public static function deleteTag(int $tagId): bool
	{
		try
		{
			$request = "DELETE FROM tag WHERE ID = ?";

			DBSession::requestDB($request, 'i', [$tagId]);

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	// В $updateFieldsValue передавать значения в виде ["название столбца, который нужно обновить" => "новое значение", ....]
	public static function updateTag(int $tagId, array $updateFieldsValue): bool
	{
		try
		{
			$couplesFieldUpdateValue = "";

			foreach ($updateFieldsValue as $field => $value)
			{
				$couplesFieldUpdateValue .= "{$field} = '{$value}',";
			}

			$couplesFieldUpdateValue = rtrim($couplesFieldUpdateValue, ',');

			$request = "UPDATE tag set {$couplesFieldUpdateValue} WHERE ID = ?";

			DBSession::requestDB($request, 'i', [$tagId]);

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	// создание связей необходимо делать после создание товаров и тегов
	public static function createLinksGoodTag(int $goodId, array $tagNames = [], array $tagIds = []): bool
	{
		try
		{
			if ($tagNames && !$tagIds)
			{
				$tagIds = self::getIdsOfTagNames($tagNames);
			}

			$couplesGoodIdTagId = "";

			foreach ($tagIds as $tagId)
			{
				$couplesGoodIdTagId .= "({$goodId}, {$tagId[0]}),";
			}

			$couplesGoodIdTagId = rtrim($couplesGoodIdTagId, ',');

			$request = "INSERT INTO good_tag (GOOD_ID, TAG_ID)
						values {$couplesGoodIdTagId};";

			return DBSession::requestDB($request);
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	private static function getIdsOfTagNames(array $tagNames): ?array
	{
		$placeholders = str_repeat('?,', count($tagNames) - 1) . '?';

		$query = "select ID
					from tag
					where NAME in ({$placeholders});";

		$DBResponse = DBSession::requestDB($query, str_repeat('s', count($tagNames)), $tagNames);

		return mysqli_fetch_all($DBResponse);
	}
}

//⠀⣞⢽⢪⢣⢣⢣⢫⡺⡵⣝⡮⣗⢷⢽⢽⢽⣮⡷⡽⣜⣜⢮⢺⣜⢷⢽⢝⡽⣝
//⠸⡸⠜⠕⠕⠁⢁⢇⢏⢽⢺⣪⡳⡝⣎⣏⢯⢞⡿⣟⣷⣳⢯⡷⣽⢽⢯⣳⣫⠇
// ⠀⠀⢀⢀⢄⢬⢪⡪⡎⣆⡈⠚⠜⠕⠇⠗⠝⢕⢯⢫⣞⣯⣿⣻⡽⣏⢗⣗⠏⠀
//⠀⠪⡪⡪⣪⢪⢺⢸⢢⢓⢆⢤⢀⠀⠀⠀⠀⠈⢊⢞⡾⣿⡯⣏⢮⠷⠁⠀⠀
//⠀⠀⠀⠈⠊⠆⡃⠕⢕⢇⢇⢇⢇⢇⢏⢎⢎⢆⢄⠀⢑⣽⣿⢝⠲⠉⠀⠀⠀⠀
//⠀⠀⠀⠀⠀⡿⠂⠠⠀⡇⢇⠕⢈⣀⠀⠁⠡⠣⡣⡫⣂⣿⠯⢪⠰⠂⠀
//⠀⠀⠀⠀⡦⡙⡂⢀⢤⢣⠣⡈⣾⡃⠠⠄⠀⡄⢱⣌⣶⢏⢊⠂⠀⠀
//⠀⠀⠀⠀⢝⡲⣜⡮⡏⢎⢌⢂⠙⠢⠐⢀⢘⢵⣽⣿⡿⠁⠁⠀
//⠀⠀⠀⠀⠨⣺⡺⡕⡕⡱⡑⡆⡕⡅⡕⡜⡼⢽⡻⠏⠀⠀⠀
//⠀⠀⠀⠀⣼⣳⣫⣾⣵⣗⡵⡱⡡⢣⢑⢕⢜⢕⡝⠀⠀
//⠀⠀⠀⣴⣿⣾⣿⣿⣿⡿⡽⡑⢌⠪⡢⡣⣣⡟
//⠀⠀⡟⡾⣿⢿⢿⢵⣽⣾⣼⣘⢸⢸⣞⡟⠀
//⠀⠀⠀⠁⠇⠡⠩⡫⢿⣝⡻⡮⣒⢽⠋⠀⠀
//       MEGA-MIND
