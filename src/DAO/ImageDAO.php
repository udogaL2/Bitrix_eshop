<?php

namespace App\Src\DAO;

use App\Core\Database\Service\DB_session;
use App\Src\Model\Image;
use Exception;

class ImageDAO
{
	/**
	 * @return ?Image[]
	 */
	public static function getImageOfGoods(string $preparedGoodsIds, bool $isOnlyMainImages = false): ?array
	{
		try
		{
			$additionalConditionForRequest = $isOnlyMainImages ? " and i.IS_MAIN = true" : "";

			$query = "select gi.GOOD_ID, (select i.ID from image i where gi.IMAGE_ID = i.ID{$additionalConditionForRequest}) as img
						from good_image gi
						where gi.GOOD_ID in ({$preparedGoodsIds});";

			$DBResponse = DB_session::request_db($query);

			$goodIdImage = [];
			$imageIds = [];

			while ($good = mysqli_fetch_row($DBResponse))
			{
				if ($good[1])
				{
					$goodIdImage[$good[0]][] = $good[1];
					$imageIds[] = $good[1];
				}
			}

			$preparedImagesIds = join(",", $imageIds);

			$images = self::collectImagesById($preparedImagesIds);

			if (!$images)
			{
				return null;
			}

			foreach ($goodIdImage as &$item)
			{
				$item = array_map(fn($im_id): Image => $images[$im_id], $item);

				if (!$isOnlyMainImages)
				{
					self::movingMainImageToFirstPlace($item);
				}
			}

			return $goodIdImage;
		}
		catch (Exception $e)
		{
			return null;
		}
	}

	private static function collectImagesById(string $preparedImagesIds): ?array
	{
		try
		{
			$images = [];

			$query = "select *
					from image
					where ID in ({$preparedImagesIds});";

			$DBResponse = DB_session::request_db($query);

			while ($image = mysqli_fetch_assoc($DBResponse))
			{
				$images[$image["ID"]] = new Image(
					$image["PATH"], $image["HEIGHT"], $image["WIDTH"], $image["IS_MAIN"], $image["ID"]
				);
			}

			return $images;
		}
		catch (Exception $e)
		{
			return null;
		}
	}

	private static function movingMainImageToFirstPlace(array &$images): void
	{
		for ($imageIndex = 0; $imageIndex < count($images); $imageIndex++)
		{
			if ($images[$imageIndex]->isMain())
			{
				[$images[0], $images[$imageIndex]] = [$images[$imageIndex], $images[0]];
				break;
			}
		}
	}
}