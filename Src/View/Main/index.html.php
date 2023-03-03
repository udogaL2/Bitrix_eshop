<?php
/**
 * @var string $content
 * @var bool $isAdmin
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href="/reset.css" rel="stylesheet">
    <link href="/style.css" rel="stylesheet">
    <link href="/scripts.js" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>

<body>
<div class="overlay"></div>
<header>
        <div class="header">
            <?php if (!$isAdmin): ?>
            <a class="logo-fon" href="/">
                <div ><div class="logo"></div></div>
            </a>
            <div class="div-search">
                <img src="/icons/icon-search.png" class="icon-search" alt="">
                <label>
					<form action="/" method="get" class="search-good">
                    	<input type="text" name="search_query" class="search" placeholder="Поиск по товарам" id="search-text">
                        <button type="submit" class="button-search">искать</button>
					</form>
                </label>
            </div>
        <?php endif; ?>
        <?php if ($isAdmin): ?>
            <button class="icon-admin" id="admin-icon"></button>
            <div class="popup-menu" id="admin-menu">
                <a href="/admin" class="popup-menu-item">Главная</a>
                <div class="menu-br"></div>
                <a href="/logout" class="popup-menu-item">Выйти</a>
            </div>
        <?php endif; ?>
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
<?php if($isAdmin): ?>
    <script src="/scripts.js">
        document.getElementById('admin-icon').addEventListener("click", popupMenu)
    </script>
<?php endif; ?>
</body>
</html>