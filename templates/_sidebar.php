<div class="sidebar-business no-overlay">
	<?php
		$url = $business['url'];

		if ( is_single() ){
			global $post;
			$post_slug = $post->post_name;
			$utm_medium = $post_slug;
		}

		$parts = parse_url($business['url']);
		parse_str($parts['query'], $query);
		$utm_source = $query['pid'];

		$utm_campaign = 'pages_widget';

		if ( $business['promote_on'] ){
			$utm_content = implode(',', $business['promote_on']);
		}

		$url .= ( !empty($utm_medium) ? ('&utm_medium=' . $utm_medium) : '') .
            '&utm_source=' . $utm_source .
            '&utm_campaign=' . $utm_campaign .
            ( !empty($utm_content) ? ('&utm_content=' . $utm_content) : '');
	?>
		<?php if (strpos($business['photo'],'googleapis') !== false) { ?>
			<a class="image-hold" href="<?= $url ?>" target="_blank" style="background-image:url(<?= BLANKSLATE_DIRECTORY_PLACEHOLDER_URL ?>);"></a>
		<?php } else { ?>
			<a class="image-hold" href="<?= $url ?>" target="_blank" style="background-image:url(<?= $business['photo'] ?>);"></a>
		<?php } ?>
		<div class="text">
			<a href="<?= $url ?>" target="_blank">
				<span class="category"><?= end($business['categories']) ?></span>
				<span class="name"><?= shortenString( $business['business_name'], 35 )?></span>
			</a>
			<a href="<?= $url ?>/#tabs-location" target="_blank">
				<span class="neighborhood"> <?= $business['neighborhood'] ?> </span>
				<?php if ( $business['display_address'] ){ ?>
				<span class="address"><i class="icon-location"></i><?= $business['display_address'] ?> </span>
				<?php } ?>
			</a>
		</div>
	</a>
</div>

