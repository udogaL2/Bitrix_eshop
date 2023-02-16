<?php /** @var App\Src\Model\Good[] $goods */ ?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link href="/reset.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<link href="/style.css" rel="stylesheet">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title></title>
</head>
<body>

<div class="container text-center">
<div class="content row row-cols-3">
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
</div>
</body>
</html>