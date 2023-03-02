<?php /**
 * @var Tag[]|Good[]|array[] $content
 * @var bool $isOrderSection
 * @var bool $isGoodSection
 * @var string $section
 * @var string[] $fields
 * @var string[] $errors
 * @var array $errors
 * @var array $allTag
 */

use App\Src\Model\Good;
use \App\Src\Service\HtmlService;
use App\Src\Model\Tag;

$i=1;
?>
<body>
<div class="content-admin-page">
    <div class="wrapper">
        <a href="/admin?section=goods" class="admin-page-panel">Товары</a>
        <a href="/admin?section=tags" class="admin-page-panel">Теги</a>
        <a href="/admin?section=orders" class="admin-page-panel">Заказы</a>
    </div>

    <div class="admin-page-table">
        <table>
            <?php if(!$isOrderSection): ?>
                <?php foreach($content as $item): ?>
                    <tr>
                        <th><?= $i++ ?></th>
                        <th><?= HtmlService::safe(HtmlService::cutGoodTitle($item->getName(), 30)) ?></th>
                        <th class="button"><a class="admin-page-a-edit" href="/edit/<?=$section?>/<?=$item->getId()?>">Edit</a></th>
                        <th class="button">
                            <form>
                                <input class="admin-page-content-del" type="submit" value="Delete">
                            </form>
                        </th>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if($isOrderSection): ?>
                <?php foreach($content as $item): ?>
                    <tr>
                        <th><?= $i++ ?></th>
                        <th><?= HtmlService::safe(HtmlService::cutGoodTitle($item['goodName'], 30)) ?></th>
                        <th><?= HtmlService::safe($item['status']) ?></th>
                        <th class="button"><a class="admin-page-a-edit" href="/edit/<?=$section?>/<?=$item['ID']?>" >Edit</a></th>
                        <th class="button">
                            <form>
                                <input class="admin-page-content-del" type="submit" value="Delete">
                            </form>
                        </th>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
    <a class="admin-page-a-add" style="visibility: <?= $isOrderSection ? 'hidden' : 'visible' ?>" id="add" href="#"> Add </a>
</div>

<div class="content-add">
	<form action="/admin?section=<?=$section?>" method="post">
		<div class="admin-page-content-add">
			<div class="admin-page-content-add-title">Создание нового <?=$section?></div>

	<?php if ($errors !== []):?>
		<?php foreach ($errors as $error): ?>
			<div class="alert alert-danger" role="alert"><?= $error ?></div>
		<?php endforeach; ?>
	<?php endif; ?>

    <?php foreach($fields as $field): ?>
		<label class="admin-add-tag">
			<input type="text" class="admin-page-content-add-input" name="dataInput[]" placeholder="Введите <?= $field ?>" minlength="1" maxlength="30" >
		</label>
	<?php endforeach; ?>
    <ul class="table-tag-add" style="visibility: <?= $isGoodSection ? 'visible' : 'hidden' ?>">
    <?php foreach($allTag as $key => $value):?>
            <label>
                <input class="checkbox" name="tagsInput[<?=$key?>]" type="checkbox" >
<!--				<input name="tagsInput[]" value="--><?php //= $key ?><!--" style="visibility: hidden">-->
                <div class="admin-tag"> <?= $value ?></div>
            </label>
    <?php endforeach;?>
    </ul>
			<input class="admin-page-content-add-a" type="submit" value="Add">
		</div>
	</form>
</div>
</body>
