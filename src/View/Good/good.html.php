<?php

use App\Config\Config;
use App\Src\Model\Good;
use \App\Src\Service\HtmlService;

/**
 * @var Good[] $goods
 * @var $pages
 * @var $currentPage
 * @var $lastPage
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600&display=swap" rel="stylesheet">
    <link href="/reset.css" rel="stylesheet">
    <link href="/style.css" rel="stylesheet">
    <title>EShop</title>
</head>
<body>

<div class="index-container">
    <div class="goods">
        <?php foreach($goods as $good): ?>
            <a class="goods-item-a" href="/product/<?=$good->getId()?>">
                <div class="goods-item">
                    <img class="img-good" src="/<?= $good->getImages()[0]->getPath() ?>">
                    <div class="br"></div>
                    <div class="good-text-container">
                        <div class="title-price-container">
                            <div class="good-name"><?= HtmlService::cutGoodTitle($good->getName()) ?></div>
                            <div class="vl"></div>
                            <div class="good-price"><?= $good->getPrice() ?> ₽</div>
                        </div>
                        <div class="good-description"><?= HtmlService::cutGoodDescription($good->getShortDesc())?></div>
                        <div class="tag-good"><?=HtmlService::concatTheFirstThreeGoodTags($good->getTags())?></div>
                    </div>
                    <div class="br"></div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <ul class="pagination">
        <li class="pagination-item <?= ($currentPage == Config::FIRST_PAGE_ON_PAGINATION) ? 'pagination-item-no-active' : '' ?>">
            <a href="/page/<?= Config::FIRST_PAGE_ON_PAGINATION ?>"><?= "<<" ?></a>
        </li>

        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?= ($currentPage === $page) ? 'pagination-item-active' : '' ?>">
                <a href="/page/<?= $page ?>"><?= $page ?></a>
            </li>
        <?php endforeach ?>

        <li class="pagination-item <?= ($currentPage === $lastPage) ? 'pagination-item-no-active' : '' ?>">
            <a href="/page/<?= $lastPage ?>"><?= ">>"?></a>
        </li>
    </ul>
</div>

</body>
</html>