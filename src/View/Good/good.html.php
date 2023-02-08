<?php /** @var App\Src\Model\Good[] $goods */ ?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link href="../reset.css" rel="stylesheet">
	<link href="../style.css" rel="stylesheet">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title></title>
</head>
<body>

<div class="content">
		<?php foreach($goods as $good): ?>
			<div class="goods-item">
				<div class="img-good"></div>
				<div class="title-good">Good №<?= $good->getId() ?></div>
				<div class="description-good">Description of Good: <?= $good->getShortDesc() ?></div>
				<div class="price-good">Price of Good: <?= $good->getPrice() ?> RUB</div>
<!--				<div class="tag-good">Tags of Good:-->
<!--					--><?php //foreach ($good->getName() as $tag): ?>
<!--					<div>-->
<!--						--><?//= $tag ?>
<!--					</div>-->
<!--					--><?php //endforeach; ?>
<!--				</div>-->
			</div>
		<?php endforeach; ?>
</div>
</body>
</html>