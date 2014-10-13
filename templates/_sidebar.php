<div class="sidebar-business" style="background-image:url(<?= $business['photo'] ?>);">
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
			$utm_content = implode(',', $business['promote_on']);
		}

		$url .= '&utm_medium=' . $utm_medium .
            '&utm_source=' . $utm_source .
            '&utm_campaign=' . $utm_campaign .
            ($utm_content ? ('&utm_content=' . $utm_content) : '') . '"';
	?>
	<a href="<?= $url ?>" class="info-hold" target="_blank">
		<div class="text">
			<h4><?= shortenString( $business['business_name'], 35 )?></h4>
			<h5><?= end($business['categories']) ?></h5>
		</div>
	</a>
</div>

