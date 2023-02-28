<?php

use App\Config\Config;
use App\Src\Model\Good;
use App\Src\Model\Tag;
use \App\Src\Service\HtmlService;
use App\Src\Service\TagService;

/**
 * @var Tag[] $tags
 * @var Good[] $goods
 * @var $pages
 * @var $currentPage
 * @var $lastPage
 * @var $searchQuery
 */
?>

<body>
<div class="content-index">
<div class="index-container">
    <div>
	<div class="tags">
		<?php foreach ($tags as $tag): ?>
			<div>
				<label>
					<input class="checkbox" type="checkbox" <?= TagService::isChecked($tag->getId()) ?>>
					<a class="good-price" href="/page/1
						<?= HtmlService::createSearchRequest($tag->getId(), $searchQuery) ?>"><?= $tag->getName() ?>
					</a>
				</label>
			</div>
		<?php endforeach; ?>
	</div>
    </div>
    <div class="goods-container">
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
    </div>

</div>

        <ul class="pagination">
            <li>
                <a class="pagination-item <?= ($currentPage == Config::FIRST_PAGE_ON_PAGINATION) ? 'pagination-item-no-active' : '' ?>" href="/page/<?= Config::FIRST_PAGE_ON_PAGINATION.HtmlService::createSearchRequest(searchSubstr: $searchQuery) ?>" ><?= "<<" ?></a>
            </li>

            <?php foreach ($pages as $page): ?>
                <li>
                    <a class="pagination-item <?= ($currentPage === $page) ? 'pagination-item-active' : '' ?>" href="/page/<?= $page.HtmlService::createSearchRequest(searchSubstr: $searchQuery) ?>" class="pagination-item-a"><?= $page ?></a>
                </li>
            <?php endforeach ?>

            <li >
                <a class="pagination-item <?= ($currentPage === $lastPage) ? 'pagination-item-no-active' : '' ?>" href="/page/<?= $lastPage.HtmlService::createSearchRequest(searchSubstr: $searchQuery) ?>" class="pagination-item-a"><?= ">>"?></a>
            </li>
        </ul>
</div>
</body>

