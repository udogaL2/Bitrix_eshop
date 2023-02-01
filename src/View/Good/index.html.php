<?php /** @var Good[] $goods */ ?>

<div class="grid text-center">
	<div class="row ">
		<?php foreach($goods as $item): ?>
			<div class="goods-item">
				<div class="img-good"></div>
				<div class="title-good">Good №<?= $item ?></div>
				<div class="description-good">Description of Good №<?= $item ?></div>
				<div class="price-good">Price of Good №<?= $item ?></div>
				<div class="tag-good">Tag of Good №<?= $item ?></div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
