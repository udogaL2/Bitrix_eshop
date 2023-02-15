<?php

namespace App\Src\DAO;

use App\Core\Database\Service\DBSession;
use App\Src\Model\Image;
use Exception;

class ImageDAO
{
	/**
	 * @return ?Image[]
	 */
	public static function getImageOfGoods(array $preparedGoodsIds, bool $isOnlyMainImages = false): ?array
	{
		try
		{
			$additionalConditionForRequest = $isOnlyMainImages ? " and i.IS_MAIN = true" : "";

			$placeholders = str_repeat('?,', count($preparedGoodsIds) - 1) . '?';

			$query = "select gi.GOOD_ID, (select i.ID from image i where gi.IMAGE_ID = i.ID{$additionalConditionForRequest}) as img
						from good_image gi
						where gi.GOOD_ID in ({$placeholders});";

			$DBResponse = DBSession::requestDB($query, str_repeat('i', count($preparedGoodsIds)), $preparedGoodsIds);

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

			$images = self::collectImagesById($imageIds);

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

	private static function collectImagesById(array $preparedImagesIds): ?array
	{
		try
		{
			$images = [];

			$placeholders = str_repeat('?,', count($preparedImagesIds) - 1) . '?';

			$query = "select *
					from image
					where ID in ({$placeholders});";

			$DBResponse = DBSession::requestDB($query, str_repeat('i', count($preparedImagesIds)), $preparedImagesIds);

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