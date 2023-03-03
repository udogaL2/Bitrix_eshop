<?php

namespace App\Src\Service;

use App\Config\Config;
use App\Src\DAO\GoodDAO;
use App\Src\Model\Good;
use DateInterval;

class GoodService
{

    private static int $numberOfGoodsCache;
    private static \DateTime $cacheExpires;
   // private \DateInterval $TTL;

    /** @var DateInterval|null $TTL */
    public function __construct(DateInterval $TTL = null)
    {
        $TTL = $TTL ?? (new DateInterval('P0M'));

        self::$numberOfGoodsCache = GoodDAO::getAvailableCount();
        self::$cacheExpires = (new \DateTime)->add($TTL);
    }

    public function getGoodInfo($id) : Good|null
    {
        return GoodDAO::getCurrentGoodById($id);
    }

    public function getNumberOfGoods() : int|null
    {
        if (self::$cacheExpires < (new \DateTime()))
        {
            self::$numberOfGoodsCache = GoodDAO::getAvailableCount();
        }
        return self::$numberOfGoodsCache;
    }

	public static function isGoodAvailableById(int $id): bool
	{
		return GoodDAO::isIdOfGoodAvailable($id);
	}

    public function setTLL(DateInterval $TTL): void
    {
        self::$cacheExpires = (new \DateTime)->add($TTL);
    }

	// $type - категория товара (например, один из тегов), $name - название товара
	public static function generateGoodCode(string $type, string $name): string
	{
		$typeInEng = self::convertRuToEng($type);
		$nameInEng = self::convertRuToEng($name);

		$step = 0;
		while (true)
		{
			$codeType = strtoupper(
				$typeInEng[$step % strlen($typeInEng)] . $typeInEng[(intdiv((strlen($typeInEng) - $step - 1), 2)) % strlen(
					$typeInEng
				)] . $typeInEng[(strlen($typeInEng) - $step - 1) % strlen($typeInEng)]
			);
			$codeName = strtoupper(
				$nameInEng[$step % strlen($nameInEng)]
				. $nameInEng[(intdiv((strlen($nameInEng) - $step - 1), 3)) % strlen(
					$nameInEng
				)]
				. $nameInEng[(intdiv((strlen($nameInEng) - $step - 1), 3) * 2) % strlen($nameInEng)]
				. $nameInEng[(strlen($nameInEng) - $step - 1) % strlen($nameInEng)]
			);

			$goodCode = $codeType . "-" . $codeName;

			if (!file_exists(Config::ROOT . "/Public/static/" . $goodCode))
			{
				break;
			}
			$step += 1;
		}

		return $goodCode;
	}

	private static function convertRuToEng(string $value): string
	{
		$converter = [
			'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zh',
			'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
			'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
			'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '', 'э' => 'e', 'ю' => 'yu',
			'я' => 'ya',

			'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'E', 'Ж' => 'Zh',
			'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
			'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
			'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch', 'Ь' => '', 'Ы' => 'Y', 'Ъ' => '', 'Э' => 'E', 'Ю' => 'Yu',
			'Я' => 'Ya',

			' ' => '', '.' => '', ',' => '', '?' => '', '/' => '', '!' => '', '#' => '', '@' => '', '"' => '',
			"'" => '', "№" => '', '&' => '', '(' => '', ')' => '', '-' => '', '_' => '', '=' => '', '+' => '',
			'*' => '', '`' => '', '~' => '', '$' => '', ';' => '', ':' => '', '<' => '', '>' => '', '[' => '',
			'{' => '', ']' => '', '}' => '', '|' => '', '\\' => '',
		];

		return strtr($value, $converter);
	}
}