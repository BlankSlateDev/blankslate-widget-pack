<div class="blankslate-tile">
	<div class="content-hold">
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
	            ($utm_content ? ('&utm_content=' . $utm_content) : '');
		?>
		<a class="image-hold" href="<?= $url ?>" target="_blank">
			<?php if (strpos($business['photo'],'googleapis') !== false) { ?>
      <img src="<?= BLANKSLATE_DIRECTORY_PLACEHOLDER_URL ?>" alt="<?=$business['business_name']?>" >
    	<?php } else { ?>
			<img src="<?= $business['photo'] ?>" alt="business-photo">
			<?php } ?>
		</a>
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
</div>