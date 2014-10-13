<div class="col-1-3 showcase-item" style="background-image:url(<?= $business['photo'] ?>);">
	<?php
		$url = $business['url'];

		global $post;
		$post_slug = $post->post_name;
		$utm_medium = $post_slug;

		$parts = parse_url($business['url']);
		parse_str($parts['query'], $query);
		$utm_source = $query['pid'];

		$utm_campaign = 'pages_widget';

		if ( $business['promote_on'] ){
		  foreach ($business['promote_on'] as $promotion) {
		    $utm_content .= $promotion . ',';
		  }
		  $utm_content = rtrim( $utm_content, ",");
		}

		$url .= '&utm_medium=' . $utm_medium .
            '&utm_source=' . $utm_source .
            '&utm_campaign=' . $utm_campaign .
            ($utm_content ? ('&utm_content=' . $utm_content) : '') . '"';
	?>
	<a href="<?= $url ?>" class="info-hold" target="_blank">
		<h4><?= $business['business_name'] ?></h4>
		<h5><?= end($business['categories']) ?></h5>
	</a>
</div>
