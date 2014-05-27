<?php
/*
Plugin Name: BlankSlate Directory Business Loop
Description: Searches for and displays four businesses based on criteria
Author: Blankslate (DH)
Version: 1.0.0
*/

class BlankSlateDirectoryBusinessLoopWidget extends WP_Widget {
	function BlankSlateDirectoryBusinessLoopWidget() {
		$widget_ops = array(
			'classname' => 'BlankSlateDirectoryBusinessLoopWidget', 
			'description' => 'Searches for and displays four businesses based on criteria' );

		$this->WP_Widget('BlankSlateDirectoryBusinessLoopWidget', 'BlankSlate Directory Business Loop', $widget_ops);
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
		  <label for="<?=$this->get_field_id('keys'); ?>">Keys (4, comma separated):
			<input class="widefat" id="<?=$this->get_field_id('keys'); ?>" name="<?=$this->get_field_name('keys'); ?>" type="text" value="<?=$instance['keys'];?>" />
		  </label>
		</p>
		
		<?php
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = esc_attr( strip_tags($new_instance['title']) );
		$instance['business-category'] = esc_attr( strip_tags($new_instance['business-category']) );
		$instance['address'] = esc_attr( strip_tags($new_instance['address']) );
		$instance['keys'] = esc_attr( strip_tags($new_instance['keys']) );

		return $instance;
	}
	
	function widget($args, $instance) {
	
		global $post;
	
		extract( $args );
		
		global $default_results, $default_search, $hide_location_search, $detect_location, $default_location, $near, $q, $cat, $featured_tier;
		$title = apply_filters( 'widget_title', $instance['title'] );
		$site_root_url = home_url();

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

		echo $before_widget; ?>

			<div class="bs-widget-pack blankslate-business-loop">
				<?php if($instance['title']): ?>
					<header>
						<h3><?= $instance['title'] ?></h3>
					</header>
				<?php endif; ?>
			
				<?php
					$featured = new SearchResults(null, $queryParameters);

					if( $featured->call() === True ){
						$results = $featured->getData();
						$businesses = $results['data'];
					} 
				?>
					
				<ul class="blankslate-business-list">
					<?php 
						for($f = 0; $f < 4; $f++){
							$business = current($businesses);
							next($businesses);
							$l_count = $f+1;
							
							include BLANKSLATE_DIRECTORY_DIR.'/views/featured-part.php';
						}
					?>
				</ul>
			</div>

		<?php echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateDirectoryBusinessLoopWidget");'));