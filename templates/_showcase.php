<div class="col-1-3 showcase-item" style="background-image:url(<?= $business['photo'] ?>);">
	<a href="<?= $business['url'] ?>" class="info-hold" target="_blank">
		<h4><?= $business['business_name'] ?></h4>
		<h5><?= end($business['categories']) ?></h5>
	</a>
</div>
