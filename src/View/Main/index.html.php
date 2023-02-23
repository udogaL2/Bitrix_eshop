<?php
/** @var string $content */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="/public/reset.css" rel="stylesheet">
    <link href="/public/style.css" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
<header>
    <div class="header">
		<a class="logo-fon" href="/">
			<div ><div class="logo"></div></div>
		</a>

        <div class="div-search">
            <img src="/public/icons/icon-search.png" class="icon-search">
            <label>
                <input type="text" class="search" placeholder="Поиск по товарам">
            </label>
            <a class="button-search" href="#">Поиск</a>
        </div>
        <a href="\auth" class="icon-admin"></a>
    </div>
</header>

<div class="content">
    <?= $content ?>
</div>

<footer>
    <div class="footer">
        <div class="about">
            <p class="about-item">О компании</p>
            <a class="about-item">О нас</a>
            <a class="about-item">Отзывы</a>
        </div>
        <div class="follow">
            <p class="follow-item">Будьте с нами</p>
            <div class="follow-icon-twitter"></div>
            <a class="follow-item">Telegram</a>
            <div class="follow-icon-gmail"></div>
            <a class="follow-item">gmail</a>
        </div>
    </div>
</footer>
</body>
</html>