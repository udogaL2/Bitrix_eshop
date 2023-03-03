<?php
/** @var \App\Src\Model\Tag $content */

use App\Src\Service\HtmlService;

?>


<body>
<div class="admin-page-content-edit">
<div class="admin-page-content-add-title">Редактирование Тега</div>
<hr class="hr-edit">
    <form class='detail-form' action="" method="">
    <?php foreach($content as $item):?>
        <?php foreach ($item as $key => $value):?>
            <div class="subtitle-input"><?= HtmlService::safe($key) ?></div>
            <label>
                <input type="text" class="admin-page-content-edit-input" value="<?=HtmlService::safe($value)?>">
            </label>
        <?php endforeach;?>
    <?php endforeach;?>
    <button type="submit" class="admin-page-content-edit-button">Редактировать</button>
    </form>
</div>
</body>

