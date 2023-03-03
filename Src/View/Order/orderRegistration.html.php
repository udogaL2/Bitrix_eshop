<?php
/**
 * @var App\Src\Model\Good $good
 * @var string[] $errors
 */

use App\Src\Service\HtmlService;

?>
<body>
<div class="container text-center">
<div class="content-order-registration">
    <?php if ($errors !== []):?>
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger" role="alert"><?= $error ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
	<div class="content-order-registration-title">Оформление заказа</div>
		<div class="order-registration-good">
			<img class="order-registration-good-img" src="/<?= $good->getImages()[0]->getPath() ?>">
			<div class="order-registration-description"><?= HtmlService::safe($good->getShortDesc()) ?></div>
			<div class="order-registration-price"><?= HtmlService::safe($good->getPrice()) ?> RUB</div>
		</div>
    <form class="order-registration-form" action="/order/<?= HtmlService::safe($good->getId()) ?>" method="POST">
		<div class="div-content-order-registration-first-name">
			<label>
				<input type="text" class="content-order-registration-first-name" name="cName" placeholder="Введите имя" minlength="1" maxlength="15" required>
			</label>
			<span class="validity"></span>
		</div>
		<div class="div-content-order-registration-last-name">
			<label>
				<input type="text" class="content-order-registration-last-name" name="cSurname" placeholder="Введите фамилию" minlength="1" maxlength="15" required>
			</label>
			<span class="validity"></span>
		</div>
		<div class="div-content-order-registration-tel">
			<label>
				<input type="tel" class="content-order-registration-tel" name="cPhone" placeholder="Введите телефон"  minlength="10" maxlength="12"
					   required pattern="\+?[0-9]{11}">
			</label>
			<span class="validity"></span>
		</div>
		<div class="div-content-order-registration-last-email">
			<label>
				<input type="email" class="content-order-registration-email" name="cEmail" placeholder="Введите почту" required>
			</label>
			<span class="validity"></span>
		</div>
		<input class="content-order-registration-button" type="submit" value="Оформить заказ">
	</form>
</div>
</div>

</body>