<?php
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="/reset.css" rel="stylesheet">
    <link href="/style.css" rel="stylesheet">
    <link href="/scripts.js" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
<div class="admin-page-content-edit">
    <div class="admin-page-content-add-title">Редактирование заказа</div>
    <hr class="hr-edit">
    <div class="subtitle-input">Id order</div>
    <label>
        <input type="text" class="admin-page-content-edit-input" value="<?=$content -> getId()?>">
    </label>
    <div class="subtitle-input">Id good</div>
    <label>
        <input type="text" class="admin-page-content-edit-input" value="<?= $content -> getGoodId() ?>">
    </label>
    <div class="subtitle-input">Status order</div>
    <label>
        <input type="text" class="admin-page-content-edit-input" value="<?= $content -> getStatus() ?>">
    </label>
    <div class="subtitle-input">Address</div>
    <label>
        <input type="text" class="admin-page-content-edit-input" value="<?= $content -> getAddress() ?>">
    </label>
    <div class="subtitle-input">Price</div>
    <label>
        <input type="text" class="admin-page-content-edit-input" value="<?= $content -> getPrice() ?>">
    </label>
    <a class="admin-page-content-edit-button" href="#"> Редактировать </a>
</div>
</body>
</html>
