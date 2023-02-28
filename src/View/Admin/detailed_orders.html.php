<?php
/** @var \App\Src\Model\Order $content */
?>

<body>
<div class="admin-page-content-edit">
    <div class="admin-page-content-add-title">Редактирование заказа</div>
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
    <button type="submit" class="admin-page-content-edit-button">Редактировать</button>
    </form>
</div>
</body>
