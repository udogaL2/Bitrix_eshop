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
	<title>Оформление заказа</title>
</head>
<body>
<div class="container text-center">
<div class="content-order-registration">
	<div class="content-order-registration-title">Оформление заказа</div>
	<form action="/orderPlaced/<?= $good->getId() ?>" method="POST">
		<div class="order-registration-good">
			<img class="order-registration-good-img" src="/<?= $good->getImages()[0]->getPath() ?>">
			<div class="order-registration-description"><?= $good->getShortDesc() ?></div>
			<div class="order-registration-tag">
            <?php foreach ($good->getTags() as $tag): ?>
				<div><?= $tag->getName() ?></div>
			<?php endforeach; ?>
            </div>
			<div class="order-registration-price"><?= $good->getPrice() ?> RUB</div>
		</div>
		<div class="div-content-order-registration-first-name">
			<label>
				<input type="text" class="content-order-registration-first-name" name="c_name" placeholder="Введите имя" minlength="3" maxlength="15" required>
			</label>
			<span class="validity"></span>
		</div>
		<div class="div-content-order-registration-last-name">
			<label>
				<input type="text" class="content-order-registration-last-name" name="c_surname" placeholder="Введите фамилию" minlength="3" maxlength="15" required>
			</label>
			<span class="validity"></span>
		</div>
		<div class="div-content-order-registration-tel">
			<label>
				<input type="tel" class="content-order-registration-tel" name="c_phone" placeholder="Введите телефон"  minlength="10" maxlength="12"
					   required pattern="[0-9]{11}">
			</label>
			<span class="validity"></span>
		</div>
		<div class="div-content-order-registration-last-email">
			<label>
				<input type="email" class="content-order-registration-email" name="c_email" placeholder="Введите почту" required>
			</label>
			<span class="validity"></span>
		</div>

		<input class="content-order-registration-button" type="submit" value="Оформить заказ">
<!--	<a class="content-order-registration-button" href="#">Оформить заказ</a>-->
	</form>
</div>
</div>

</body>
</html>