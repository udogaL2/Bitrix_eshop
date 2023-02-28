<?php
use \App\Src\Service\AdminService;

/** @var \App\Src\Model\Good $content */
/** @var array $allTag */
/** @var array $tagGood */
?>

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

