<?php

namespace App\Src\Service;

use App\Src\Model\Tag;

class HtmlService
{
    public static function cutGoodTitle($title) : string
    {
        if (mb_strlen($title) < 26)
        {
            return $title;
        }
        return mb_substr($title, 0, 25) . '...';
    }

    public static function cutGoodDescription($title) : string
    {
        if (mb_strlen($title) < 265)
        {
            return $title;
        }
        return mb_substr($title, 0, 266) . '...';
    }

    /** @var Tag[] $tags */
    public static function concatTheFirstThreeGoodTags(array $tags) : string
    {
        $result = '';
        $n = min(count($tags), 3);
        for ($i = 0; $i < $n; $i++)
        {
            $result .= $tags[$i]->getName() . ', ';
        }
        return rtrim($result, ', ');
    }
}