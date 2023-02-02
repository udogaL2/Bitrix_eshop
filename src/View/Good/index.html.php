<?php /** @var App\Src\Model\Good[] $goods */ ?>

<div class="grid text-center">
	<div class="row ">
		<?php foreach($goods as $good): ?>
			<div class="goods-item">
				<div class="img-good"></div>
				<div class="title-good">Good №<?= $good->getId() ?></div>
				<div class="description-good">Description of Good: <?= $good->getShortDesc() ?></div>
				<div class="price-good">Price of Good: <?= $good->getPrice() ?> RUB</div>
<!--				<div class="tag-good">Tags of Good:-->
<!--					--><?php //foreach ($good->getName() as $tag): ?>
<!--					<div>-->
<!--						--><?//= $tag ?>
<!--					</div>-->
<!--					--><?php //endforeach; ?>
<!--				</div>-->
			</div>
		<?php endforeach; ?>
	</div>
</div>
