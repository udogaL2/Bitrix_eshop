<?php

namespace App\Src\DAO;

use App\Core\Database\Service\DB_session;
use App\Src\Model\Tag;
use Exception;

class TagDAO
{
	/**
	 * @return Tag[]|null
	 */
	public static function getTagsOfGoods(string $preparedGoodsIds): ?array
	{
		try
		{
			$query = "select gt.GOOD_ID, (select t.NAME from tag t where gt.TAG_ID = t.ID) as tag
					from good_tag gt
					where gt.GOOD_ID in ({$preparedGoodsIds});";

			$DBResponse = DB_session::request_db($query);

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
}