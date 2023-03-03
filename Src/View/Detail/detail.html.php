<?php /** @var App\Src\Model\Good $good */

use App\Src\Service\HtmlService; ?>


<body>
<div class="content-detail">
	<div class="detail-list">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/<?= $good->getImages() ? $good->getImages()[0]->getPath() : 'icons/logo.png' ?>" class="d-block w-100 detail-list-img" alt="...">
                </div>
                <?php foreach (array_slice($good->getImages(),1) as $item):?>

                <div class="carousel-item">
                    <img src="/<?= $item->getPath() ?>" class="d-block w-100 detail-list-img" alt="...">
                </div>
                <?php endforeach?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <div class="detail-text">
            <div class="detail-list-title"><?= HtmlService::safe($good->getName()) ?></div>
            <div class="detail-list-description"><?= HtmlService::safe($good->getFullDesc()) ?></div>
            <div class="detail-list-producer"><?= 'producer' ?></div>
            <div class="detail-list-tag">
            <?php foreach ($good->getTags() as $tag): ?>
                <div><?= HtmlService::safe($tag->getName()) ?></div>
            <?php endforeach; ?>
            </div>
        </div>
	</div>
	<div class="detail-price-buy">
		<div class="detail-price"><?= HtmlService::safe($good->getPrice()) ?> ₽</div>
		<a href="/order/<?= HtmlService::safe($good->getId()) ?>" class="detail-button-buy">Buy</a>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
</body>


