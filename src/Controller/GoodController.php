<?php

namespace App\Src\Controller;

use App\Core\Routing\Router;
use App\Src\Model\Image;
use App\Src\Model\Tag;
use App\Src\Model\Good;
use App\Core\Database\Service\DB_session;

class GoodController extends BaseController
{
    public function getDetailedGoodAction($id)
    {
        try
        {
            $good_request = DB_session::request_db(
                'SELECT * FROM good where ID = ?;',
                'i',
                [$id]
            );
        }
        catch (\Exception $e)
        {
            //Logger::addError($e->getLine() . ', ' . $e->getMessage());
            $this->notFoundAction();
            return;
        }

        if ($good_request === null)
        {
            $this->notFoundAction();
            return;
        }
        if (mysqli_num_rows($good_request) === 0)
        {
            $this->notFoundAction();
            return;
        }

        $good_result = mysqli_fetch_assoc($good_request);

        try
        {
            $tags_request = DB_session::request_db(
                'SELECT ID, NAME FROM tag t
            INNER JOIN good_tag gt on t.ID = gt.TAG_ID AND GOOD_ID = ?;',
                'i',
                [$id]
            );

            $tags = [];
            if ($tags_request !== null)
            {
                if (mysqli_num_rows($tags_request) !== 0)
                {
                    while ($tag = mysqli_fetch_assoc($tags_request))
                    {
                        $tags[] = new Tag($tag['NAME'], $tag['ID']);
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            //Logger::addError($e)
            $tags = [];
        }

        try
        {
            $images_request = DB_session::request_db(
                'SELECT ID, PATH, HEIGHT, WIDTH, IS_MAIN FROM image img
            INNER JOIN good_image g_img on img.ID = g_img.IMAGE_ID AND GOOD_ID = ?;',
                'i',
                [$id]
            );

            $images = [];
            if ($images_request !== null)
            {
                if (mysqli_num_rows($images_request) !== 0)
                {
                    $name_regex = '#[\p{L}\d]+\.\p{L}+$#u';
                    while ($img = mysqli_fetch_assoc($images_request))
                    {
                        $matches = [];
                        preg_match($name_regex, $img['PATH'], $matches);
                        $name = array_shift($matches);
                        $directory = str_replace($name, '', $img['PATH']);
                        $images[] = new Image($name, $img['IS_MAIN'], $directory, $img['WIDTH'], $img['HEIGHT']);
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            //Logger::addError($e)
            $images = [];
        }


        $good = new Good(
            $good_result['NAME'],
            $good_result['PRICE'],
            $good_result['GOOD_CODE'],
            $good_result['SHORT_DESC'],
            $good_result['FULL_DESC'],
            $good_result['ID'],
            new \DateTime($good_result['DATE_UPDATE']),
            new \DateTime($good_result['DATE_CREATE']),
            $good_result['IS_ACTIVE'],
            $images,
			$tags
        );
        try
        {
            echo self::view('Main/index.html', [
                'content' => self::view('Detail/index.html', [
                    'good' => $good,
                ]),
            ]);
        }
        catch (PathException $e)
        {
            //Logger::addError($e->getMessage());
            $this->notFoundAction();
        }
    }
}