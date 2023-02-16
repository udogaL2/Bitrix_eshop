<?php

use App\Config\Config;
use App\Src\Model\Good;
use App\Src\Service\IndexService;

/**
 * @var Good[] $goods
 * @var $pages
 */

$currentPage=(int)substr($_SERVER["REQUEST_URI"], 1);
$lastPage= IndexService::getLastPageForPagination();
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link href="/reset.css" rel="stylesheet">
	<link href="/style.css" rel="stylesheet">
</head>
<body>

<div class="index-container">
	<div class="goods">
		<?php foreach($goods as $good): ?>
			<a class="goods-item" href="/product/<?=$good->getId()?>">
				<div>
					<img class="img-good" src="/<?= $good->getImages()[0]->getPath() ?>">
					<div class="title-good">Good №<?= $good->getId() ?></div>
					<div class="title-good">Good Name:<?= $good->getName() ?></div>
					<div class="description-good">Description of Good: <?= $good->getShortDesc() ?></div>
					<div class="price-good">Price of Good: <?= $good->getPrice() ?> RUB</div>
					<div class="tag-good">Tags of Good:
						<?php foreach ($good->getTags() as $tag): ?>
							<div>
								<?= $tag->getName() ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</a>
		<?php endforeach; ?>
	</div>

	<ul class="pagination">
		<li class="pagination-item <?= ($currentPage === Config::FIRST_PAGE_ON_PAGINATION) ? 'pagination-item-no-active' : '' ?>">
			<a href="/<?= Config::FIRST_PAGE_ON_PAGINATION ?>"><?= "<<" ?></a>
		</li>

		<?php foreach ($pages as $page): ?>
			<li class="pagination-item <?= ($currentPage === $page) ? 'pagination-item-active' : '' ?>">
				<a href="/<?= $page ?>"><?= $page ?></a>
			</li>
		<?php endforeach ?>

		<li class="pagination-item <?= ($currentPage === $lastPage) ? 'pagination-item-no-active' : '' ?>">
			<a href="/<?= $lastPage ?>"><?= ">>"?></a>
		</li>
	</ul>
</div>

</body>
</html>