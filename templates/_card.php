<div class="pages-card">
	<div class="inner">
		<?php $thumb = '/wp-content/themes/incontext/timthumb.php?w=300&h=200&zc=1&src='; ?>
		<?php if( $show_photo == true ): ?>
		<a class="link-wrap" href="<?= $this->url ?>" target="_blank">
			<div class="img-hold">
				<img src="<?= $thumb . $this->photo ?>" />
			</div>
		</a>
		<?php endif; ?>
		<div class="text-hold">
			<a class="card-name" href="<?= $this->url ?>" target="_blank">
				<?= shortenString( $this->name, 80 ); ?>
			</a>
			<a class="card-address" href="<?= $this->url ?>/#tabs-location" target="_blank">
				<img src="http://pages.blankslate.com/img/pages-marker.png" alt="map-marker">
				<div class="text">
					<?= shortenString( $this->display_address, 25 ); ?>
					</br>
					<?= trim($this->city); ?>,
					<?= trim($this->state); ?>
					<?= trim($this->zip); ?>
				</div>
			</a>
		</div>
	</div>
</div>
