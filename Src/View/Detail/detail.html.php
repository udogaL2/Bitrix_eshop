<?php /** @var App\Src\Model\Good $good */?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link href="/reset.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<link href="/style.css" rel="stylesheet">
<!--    <link href="/DetailStyle.css" rel="stylesheet">-->
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title></title>
</head>
<body>
<div class="content-detail">
	<div class="detail-list">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/<?= $good->getImages()[0]->getPath() ?>" class="d-block w-100 detail-list-img" alt="...">
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
            <div class="detail-list-title"><?= $good->getName() ?></div>
            <div class="detail-list-description"><?= $good->getFullDesc() ?></div>
            <div class="detail-list-producer"><?= 'producer' ?></div>
            <div class="detail-list-tag">
            <?php foreach ($good->getTags() as $tag): ?>
                <div><?= $tag->getName() ?></div>
            <?php endforeach; ?>
            </div>
        </div>
	</div>
	<div class="detail-price-buy">
		<div class="detail-price"><?= $good->getPrice() ?> ₽</div>
		<a href="/order/<?= $good->getId() ?>" class="detail-button-buy">Buy</a>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
</body>
</html>


