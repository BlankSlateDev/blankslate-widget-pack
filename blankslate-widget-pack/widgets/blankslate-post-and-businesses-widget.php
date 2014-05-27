<?php
/*
Widget Name: BlankSlate Post and Businesses
Description: Blog Post and Businesses Widget
Author: Blankslate (DH)
Version: 1.0.0
*/

class BlankSlateDirectoryPostAndBusinessesWidget extends WP_Widget {

	function BlankSlateDirectoryPostAndBusinessesWidget() {
		$widget_ops = array(
			'classname' => 'BlankSlateDirectoryPostAndBusinessesWidget', 
			'description' => 'Display BlankSlate Directory Blog Post and Businesses related to post' );
			
		$this->WP_Widget('BlankSlateDirectoryPostAndBusinessesWidget', 'BlankSlate Directory Post and Businesses', $widget_ops);
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		?>
		<p>
		  <label for="<?=$this->get_field_id('title'); ?>">Title:
			<input class="widefat" id="<?=$this->get_field_id('title'); ?>" name="<?=$this->get_field_name('title'); ?>" type="text" value="<?=$instance['title'];?>" />
		  </label>
		</p>
		<hr>
		<p>
		  <label for="<?=$this->get_field_id('post-title'); ?>">Post Title:
			<input class="widefat" id="<?=$this->get_field_id('post-title'); ?>" name="<?=$this->get_field_name('post-title'); ?>" type="text" value="<?=$instance['post-title'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('blog-category'); ?>">Blog Category:
			<input class="widefat" id="<?=$this->get_field_id('blog-category'); ?>" name="<?=$this->get_field_name('blog-category'); ?>" type="text" value="<?=$instance['blog-category'];?>" />
		  </label>
		</p>
		<hr>
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
		  <label for="<?=$this->get_field_id('keys'); ?>">Keys (6, comma separated):
			<input class="widefat" id="<?=$this->get_field_id('keys'); ?>" name="<?=$this->get_field_name('keys'); ?>" type="text" value="<?=$instance['keys'];?>" />
		  </label>
		</p>
		<p>
			<?php if ($instance['layout'] == 'left'): ?>
			<label for="<?=$this->get_field_id('left'); ?>">Left: 
			<input type="radio" name="<?=$this->get_field_name('layout'); ?>" value="left" id="<?=$this->get_field_id('left');?>" checked/>
			<label for="<?=$this->get_field_id('right'); ?>">Right:  
			<input type="radio" name="<?=$this->get_field_name('layout'); ?>" value="right" id="<?=$this->get_field_id('right');?>" /> 
			<?php else: ?>
			<label for="<?=$this->get_field_id('left'); ?>">Left: 
			<input type="radio" name="<?=$this->get_field_name('layout'); ?>" value="left" id="<?=$this->get_field_id('left');?>"/>
			<label for="<?=$this->get_field_id('right'); ?>">Right:  
			<input type="radio" name="<?=$this->get_field_name('layout'); ?>" value="right" id="<?=$this->get_field_id('right');?>" checked/>  
			<?php endif; ?>
		</p>
		
		<?php
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = esc_attr( strip_tags($new_instance['title']) );

		$instance['post-title'] = esc_attr( strip_tags($new_instance['post-title']) );
		$instance['blog-category'] = esc_attr( strip_tags($new_instance['blog-category']) );

		$instance['business-category'] = esc_attr( strip_tags($new_instance['business-category']) );
		$instance['address'] = esc_attr( strip_tags($new_instance['address']) );
		$instance['keys'] = esc_attr( strip_tags($new_instance['keys']) );
		
		$instance['layout'] = esc_attr( strip_tags($new_instance['layout']) );

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
		
		$layout = $instance['layout'];

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
		
		<div class="bs-widget-pack business-and-post-widget <?= $layout ?>">
		
			<?php if($instance['title']) { ?>
				<header><h3><?=$instance['title']?></h3></header>
			<?php } ?>
			
			<div class="content-wrapper">
				<div class="blog-post">
					<?php

					if ( $instance['post-title'] ){
						$args = array(
							'name' => $instance['post-title'],
							'posts_per_page' => 1,
						); 
					} else {
						$args = array(
							'category_name' => $instance['blog-category'],
							'posts_per_page' => 1,
						); 
					}
					
					$the_query = new WP_Query( $args );
					?>
					
					<?php if ( $the_query->have_posts() ) : ?>

	  				<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	  					<a href="<?= the_permalink() ?>">
	    				<?php the_post_thumbnail(); ?>
	    				</a>
	    				<?php the_title('<h2>', '</h2>'); ?>
	    				<?php the_excerpt('<p>', '</p>'); ?>
	  				<?php endwhile; ?>

	  				<?php wp_reset_postdata(); ?>

					<?php else:  ?>
	  					<p>
	  						<?php _e( 'Sorry, no posts matched your criteria.' ); ?>
	  					</p>
					<?php endif; ?>
				</div>

				<div class="businesses">
					<?php
						$featured = new SearchResults(null, $queryParameters);

						if( $featured->call() === True ){
							$results = $featured->getData();
							$businesses = $results['data'];
						} 
					?>
						
					<ul class="blankslate-business-list">
						<?php 
							for($f=0; $f < 6; $f++){
								$business = current($businesses);
								next($businesses);
								$l_count = $f+1;
							
								include BLANKSLATE_DIRECTORY_DIR.'/views/featured-part.php';
							}
						?>
					</ul>
				</div>
			</div>
		</div>

		<?php

		echo $after_widget;
	}

}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateDirectoryPostAndBusinessesWidget");'));