<?php
/** @var \App\Src\Model\Good $good */
?>

<div class="detail-list">
	<div class="detail-list-title">Title of Good: <?= $good->getName() ?></div>
    <?php foreach ($good->getImages() as $image): ?>
	    <div class="detail-list-img">Image of Good: <?= $image->getAbsolutePath() ?></div>
    <?php endforeach; ?>
	<div class="detail-list-description">Full description of Good: <?= $good->getFullDesc() ?></div>
	<div class="detail-list-producer">Producer of Good</div>
	<div class="detail-list-price">Price of Good: <?= $good->getPrice() ?></div>
    <div class="tag-good">Tag of Good: </div>
    <?php foreach ($good->getTags() as $tag): ?>
	    <div> <?= $tag->getName() ?></div>
    <?php endforeach; ?>
</div>
