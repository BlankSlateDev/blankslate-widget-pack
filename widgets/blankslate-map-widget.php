<?php
/*
Plugin Name: Blankslate Map Widget
Description: Map Widget for Widget Pack
Author: Blankslate (DH)
Version: 1.0.1
*/

class BlankSlateDirectoryMapWidget extends WP_Widget {

	function BlankSlateDirectoryMapWidget() {
		$widget_ops = array(
			'classname' => 'BlankSlateDirectoryMapWidget', 
			'description' => 'Display BlankSlate Directory Map and Related Info' );
			
		$this->WP_Widget('BlankSlateDirectoryMapWidget', 'BlankSlate Directory Map', $widget_ops);
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		?>
		<p>
		  <label for="<?=$this->get_field_id('title'); ?>">Title:
			<input class="widefat" id="<?=$this->get_field_id('title'); ?>" name="<?=$this->get_field_name('title'); ?>" type="text" value="<?=$instance['title'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('centerpoint'); ?>">Centerpoint for Map:
			<input class="widefat" id="<?=$this->get_field_id('centerpoint'); ?>" name="<?=$this->get_field_name('centerpoint'); ?>" type="text" value="<?=$instance['centerpoint'];?>" />
		  </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('description'); ?>">Description: </label><br>
			<textarea id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"><?= $instance['description']; ?></textarea>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('business-category'); ?>">Business Category:
			<input class="widefat" id="<?=$this->get_field_id('business-category'); ?>" name="<?=$this->get_field_name('business-category'); ?>" type="text" value="<?=$instance['business-category'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('address'); ?>">Address:
			<input class="widefat" id="<?=$this->get_field_id('address'); ?>" name="<?=$this->get_field_name('address'); ?>" type="text" value="<?=$instance['address'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('keys'); ?>">Keys (2, comma separated):
			<input class="widefat" id="<?=$this->get_field_id('keys'); ?>" name="<?=$this->get_field_name('keys'); ?>" type="text" value="<?=$instance['keys'];?>" />
		  </label>
		</p>
		<p>
			<label for="<?= $this->get_field_id('business-display') ?>">Display Businesses?
				<input type="checkbox" 
						class="widefat" 
						id="<?= $this->get_field_id('business-display'); ?>" 
						name="<?= $this->get_field_name('business-display') ?>" 
						<?php checked($instance['business-display'], 'on'); ?> >
			</label>
		</p>
		
		<?php
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = esc_attr( strip_tags($new_instance['title']) );
		$instance['description'] = $new_instance['description'];
		$instance['centerpoint'] = esc_attr( strip_tags($new_instance['centerpoint']) );
		
		$instance['business-display'] = $new_instance['business-display'];
		$instance['business-category'] = esc_attr( strip_tags($new_instance['business-category']) );
		$instance['address'] = esc_attr( strip_tags($new_instance['address']) );
		$instance['keys'] = esc_attr( strip_tags($new_instance['keys']) );

		return $instance;
	}
	
	function widget($args, $instance) {
		extract( $args );
		
		global $default_results, $default_search, $hide_location_search, $detect_location, $default_location, $near, $q, $cat, $featured_tier;
		$title = apply_filters( 'widget_title', $instance['title'] );
		$site_root_url = home_url();

		$centerpoint = $instance['centerpoint'];
		$description = $instance['description'];
		
		$businessDisplay = $instance['business-display'] ? 'true' : 'false';
		$keys = $instance['keys'];
		$address = $instance['address'];
		$businessCategory = $instance['business-category'];

		if ($address) {
			$addressArray = blankslate_get_lat_lng($address);
			$lat = $addressArray['lat'];
			$lng = $addressArray['lon'];
		}

		//If user has input keys, use them to display businesses
		//Else, use address and category
		if ($keys != '') {
			$queryParameters['keys'] = $keys;
		} else {
			$queryParameters['lat'] = $lat;
			$queryParameters['lng'] = $lng;
			$queryParameters['query'] = $businessCategory;
		}

		$queryParameters['rp'] = 12;
		 
		echo $before_widget;
		?>
		
		<div class="bs-widget-pack business-and-post-widget">
			<?php if($instance['title']): ?>
				<header><h3><?=$instance['title']?></h3></header>
			<?php endif; ?>
			
			<div class="content-wrapper">
				<div class="map">
					<?php 
						$centerpoint = urlencode($centerpoint);
						$src = "http://maps.googleapis.com/maps/api/staticmap?center=" . $centerpoint . "&zoom=15&size=350x300&scale=2&markers=" . $centerpoint;
					?>
					<a href="http://maps.google.com/maps?daddr=<?= $centerpoint ?>">
					<img src="<?= $src ?>">
					</a>
				</div>
				<div class="map-content">
				<p>
					<?= nl2br($description); ?>
				</p>

					<?php
						$featured = new SearchResults(null, $queryParameters);

						if( $featured->call() === True ){
							$results = $featured->getData();
							$businesses = $results['data'];
						} 
					?>
						
					<ul class="blankslate-business-list display-<?= $businessDisplay ?>">
						<?php for($f=0; $f < 2; $f++){
							$business = current($businesses);
							next($businesses);
							$l_count = $f+1;
							
							include BLANKSLATE_DIRECTORY_DIR.'/views/featured-part.php';
						 } ?>
					</ul>
				</div>
			</div>
		</div>
		<?php

		echo $after_widget;
	}

}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateDirectoryMapWidget");'));