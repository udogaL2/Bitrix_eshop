<?php /**
 * @var Tag[]|Good[]|array[] $content
 * @var bool $isOrderSection
 * @var string $section
 */

use App\Src\Model\Good;
use \App\Src\Service\HtmlService;
use App\Src\Model\Tag;

$i=1;
?>

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
                        <th><?= HtmlService::cutGoodTitle($item->getName(), 30) ?></th>
                        <th><a class="admin-page-a-edit" href="/edit/<?=$section?>/<?=$item->getId()?>">Edit</a></th>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if($isOrderSection): ?>
                <?php foreach($content as $item): ?>
                    <tr>
                        <th><?= $i++ ?></th>
                        <th><?= HtmlService::cutGoodTitle($item['goodName'], 30) ?></th>
                        <th><?= $item['status'] ?></th>
                        <th><a class="admin-page-a-edit" href="/edit/<?=$section?>/<?=$item['ID']?>" >Edit</a></th>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
	<a class="admin-page-a-add" href="#"> Add </a>
</div>
</body>
</html>

