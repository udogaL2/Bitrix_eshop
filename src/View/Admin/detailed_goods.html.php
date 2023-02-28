<?php
use \App\Src\Service\AdminService;

/** @var \App\Src\Model\Good $content */
/** @var array $allTag */
/** @var array $tagGood */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="/reset.css" rel="stylesheet">
    <link href="/style.css" rel="stylesheet">
<!--    <link href="/AdminEditStyle.css" rel="stylesheet">-->
<!--    <link href="/scripts.js" rel="stylesheet">-->
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
<div class="admin-page-content-edit">
<div class="admin-page-content-add-title">Редактирование Товара</div>
    <hr class="hr-edit">
    <form class='detail-form' action="" method="">
    <?php foreach($content as $item):?>
        <?php foreach ($item as $key => $value):?>
            <div class="subtitle-input"><?= $key?></div>
            <label>
                <input type="text" class="admin-page-content-edit-input" value="<?=$value?>">
            </label>
        <?php endforeach;?>
    <?php endforeach;?>
        <ul>
            <?php foreach($allTag as $key => $value):?>
            <li>
                    <div>
                        <label>
                            <input class="checkbox" type="checkbox" <?= AdminService::isCheckedTag($value, $tagGood)?>>
                            <div class="admin-tag">
                                <?= $value?>
                            </div>
                        </label>
                    </div>
               </li>
            <?php endforeach;?>
        </ul>

    <button type="submit" class="admin-page-content-edit-button">Редактировать</button>
    </form>
</div>
</body>
</html>

