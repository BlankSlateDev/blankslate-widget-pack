<div class="sidebar-business" style="background-image:url(<?= $business['photo'] ?>);">
	<a href="<?= $business['url'] ?>" class="info-hold" target="_blank">
		<div class="text">
			<h4><?= shortenString( $business['business_name'], 35 )?></h4>
			<h5><?= end($business['categories']) ?></h5>
		</div>
	</a>
</div>

