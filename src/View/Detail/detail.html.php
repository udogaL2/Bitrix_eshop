<?php /** @var App\Src\Model\Good $good */ ?>

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

<div class="content-detail">
	<div class="detail-list">
		<img class="detail-list-img" src="/<?= $good->getImages()[0]->getPath() ?>">
		<div class="detail-list-title"><?= $good->getName() ?></div>
		<div class="detail-list-description"><?= $good->getShortDesc() ?></div>
		<div class="detail-list-producer"><?= 'producer' ?></div>
        <div class="detail-list-tag">
		<?php foreach ($good->getTags() as $tag): ?>
			<div class=""><?= $tag->getName() ?></div>
		<?php endforeach; ?>
        </div>
	</div>
	<div class="detail-price-buy">
		<div class="detail-price"><?= $good->getPrice() ?> RUB</div>
		<a href="/order/<?= $good->getId() ?>" class="detail-button-buy">Buy</a>
	</div>
</div>
</body>
</html>


