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

<div class="content-order-registration">
	<div class="content-order-registration-title">Оформление заказа</div>
	<div class="order-registration-good">
		<div class="order-registration-good-img"></div>
		<div class="order-registration-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab exercitationem iste sit ullam voluptates! Accusamus iure nulla qui quod reprehenderit!</div>
		<div class="order-registration-tag">Lorem ipsum dolor sit amet, consectetur adipisicing.</div>
		<div class="order-registration-price">500 RUB</div>
	</div>
	<div class="div-content-order-registration-first-name">
		<label>
			<input type="text" class="content-order-registration-first-name" placeholder="Введите имя" minlength="3" maxlength="15" required>
		</label>
		<span class="validity"></span>
	</div>
	<div class="div-content-order-registration-last-name">
		<label>
			<input type="text" class="content-order-registration-last-name" placeholder="Введите фамилию" minlength="3" maxlength="15" required>
		</label>
		<span class="validity"></span>
	</div>
	<div class="div-content-order-registration-tel">
		<label>
			<input type="tel" class="content-order-registration-tel" placeholder="Введите телефон"  minlength="10" maxlength="12"
				   required pattern="[0-9]{11}">
		</label>
		<span class="validity"></span>
	</div>
	<div class="div-content-order-registration-last-email">
		<label>
			<input type="email" class="content-order-registration-email" placeholder="Введите почту" required>
		</label>
		<span class="validity"></span>
	</div>
	<a class="content-order-registration-button" href="#">Оформить заказ</a>
</div>

</body>
</html>