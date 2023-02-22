<?php /** @var Tag[]|Good[]|Order[] $content */

use App\Src\Model\Good;
use App\Src\Model\Order;
use App\Src\Model\Tag; ?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<link href="/reset.css" rel="stylesheet">
	<link href="/style.css" rel="stylesheet">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title></title>
</head>

<body>
<div style="margin: 100px 0 0 50px;
			display: flex;
			align-items: center">

	<div style="display: flex;
				flex-direction: column;
			">

		<a href="/admin?section=orders">
			<div style="margin: 25px 10px 10px 10px;">Заказы</div>
		</a>

		<a href="/admin?section=goods">
			<div style="margin: 25px 10px 10px 10px;">Товары</div>
		</a>

		<a href="/admin?section=tags">
			<div style="margin: 25px 10px 10px 10px;">Теги</div>
		</a>
	</div>

	<div style="margin-left: 100px;">
		<?php foreach ($content as $item):?>
			<div style="display:flex;
						align-items: flex-end">
				<div style="margin-top: 10px"> <?= $item->getName() ?> </div>
				<a href="#" style="margin-left: 15px">Edit</a>
			</div>
		<?php endforeach; ?>
	</div>

	<a href="#" style="font-size: 24px;
				margin-left: 150px"> Add </a>

</div>


</body>
</html>

