<?php

namespace App\Src\DAO;

use App\Core\Database\Service\DBSession;
use App\Src\Model\Tag;
use Exception;

// Для удаления и добавления записей в таблицу связей смотри BaseLinkedDAO.php

class TagDAO extends BaseLinkedDAO
{
	protected static string $tableName = "tag";
	protected static string $linkTableName = "good_tag";
	protected static string $primaryLinkColumn = "GOOD_ID";
	protected static string $secondaryLinkColumn = "TAG_ID";

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

	public static function getAllTags(): ?array
	{
		try
		{
			$DBResponse = DBSession::requestDB("select * from tag");

			$tags = [];

			while ($rawTag = mysqli_fetch_assoc($DBResponse))
			{
				$tags[] = new Tag($rawTag["NAME"], $rawTag["ID"]);
			}

			return $tags;
		}
		catch (Exception $e)
		{
			return null;
		}
	}

	public static function getIdsOfTagNames(array $tagNames): ?array
	{
		try
		{
			$placeholders = str_repeat('?,', count($tagNames) - 1) . '?';

			$query = "select *
					from tag
					where NAME in ({$placeholders});";

			$DBResponse = DBSession::requestDB($query, str_repeat('s', count($tagNames)), $tagNames);

			$tagsId = [];

			foreach (mysqli_fetch_all($DBResponse) as $value)
			{
				$tagsId[$value[1]] = $value[0];
			}

			return $tagsId;
		}
		catch (Exception $e)
		{
			return null;
		}
	}

    public static function updateTag()
    {
        //
    }

    public static function getTagByID(int $id) : Tag|null
    {
        //
        return null;
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
