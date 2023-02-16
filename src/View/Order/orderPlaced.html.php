<?php
/** @var string $content */
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link href="/reset.css" rel="stylesheet">
	<link href="/style.css" rel="stylesheet">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title></title>
</head>
<body>

<div class="content-order-placed">
	<img class="content-order-placed-icon" src="/src/View/icons/check.png">
	<div class="content-order-placed-title">
		<?= $content ?>
	</div>
	<a href="/" class="content-detail-back"></a>
</div>

</body>
</html>