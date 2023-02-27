<?php

namespace App\Src\Service;

class ImageService
{
	// $goodTitle - название товара, $imageCount - количество изображений
	public static function generateTitlesOfImages(string $goodTitle, int $imageCount): array
	{
		$titlePattern = "Фото {$goodTitle} №";

		return array_map(fn($imageNumber): string => hash('md5', $titlePattern . $imageNumber), range(1, $imageCount));
	}
}