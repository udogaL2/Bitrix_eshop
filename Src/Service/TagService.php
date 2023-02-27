<?php

namespace App\Src\Service;

class TagService
{
	public static function createSearchRequestForTags(int $tagId = null): string
	{
		$searchRequestForTags = self::calculateTagsIdsForSearchRequest($tagId);
		if (empty($searchRequestForTags))
		{
			return "";
		}

		return "?tags=" . $searchRequestForTags;
	}

	private static function calculateTagsIdsForSearchRequest(int $tagId = null): ?string
	{
		if (is_null($tagId))
		{
			if (!isset($_GET["tags"]) || empty(trim($_GET["tags"])))
			{
				return null;
			}

			$get = explode(" ", $_GET["tags"]);

			return implode("+", $get);
		}

		if (!isset($_GET["tags"]) || empty(trim($_GET["tags"])))
		{
			return (string)$tagId;
		}

		$get = explode(" ", $_GET["tags"]);

		if (($key = array_search((string)$tagId, $get, true)) !== false)
		{
			unset($get[$key]);
		}
		else
		{
			$get[] = (string)$tagId;
		}

		return implode("+", $get);
	}

	public static function isChecked(int $tagId): string
	{
		if (!isset($_GET["tags"]) || empty(trim($_GET["tags"])))
		{
			return "";
		}

		$get = explode(" ", $_GET["tags"]);
		if (($key = array_search((string)$tagId, $get, true)) !== false)
		{
			return "checked";
		}

		return "";
	}
}