<?php

namespace App\Src\Service;

use App\Src\DAO\GoodDAO;
use App\Src\Model\Good;

class GoodService
{

    private static int $numberOfGoodsCache;
    private static \DateTime $cacheExpires;
   // private \DateInterval $TTL;

    /** @var \DateInterval $TTL */
    public function __construct($TTL = null)
    {
        $TTL = $TTL ?? (new \DateInterval('P0M'));

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

    public function setTLL(\DateInterval $TTL): void
    {
        self::$cacheExpires = (new \DateTime)->add($TTL);
    }
}