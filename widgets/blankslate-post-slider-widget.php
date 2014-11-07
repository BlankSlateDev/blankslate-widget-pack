<?php
/*
Widget Name: BlankSlate Post Slider
Description: Displays Posts in a Slider
Author: Blankslate (DH)
Version: 1.0.0
*/

class BlankSlateDirectoryPostsSlider extends WP_Widget {

	function BlankSlateDirectoryPostsSlider() {
		$widget_ops = array(
			'classname' => 'BlankSlateDirectoryPostsSlider', 
			'description' => 'Display posts in a slider' );
			
		$this->WP_Widget('BlankSlateDirectoryPostsSlider', 'Displays posts in a slider', $widget_ops);
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		?>
		<p>
		  <label for="<?=$this->get_field_id('title'); ?>">Title:
			<input class="widefat" id="<?=$this->get_field_id('title'); ?>" name="<?=$this->get_field_name('title'); ?>" type="text" value="<?=$instance['title'];?>" />
		  </label>
		</p>
		
		<?php
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = esc_attr( strip_tags($new_instance['title']) );

		return $instance;
	}
	
	function widget($args, $instance) {
	
		global $post;
	
		extract( $args );
		global $default_results, $default_search, $hide_location_search, $detect_location, $default_location, $near, $q, $cat, $featured_tier;
		$title = apply_filters( 'widget_title', $instance['title'] );
		$site_root_url = home_url();

		$args = array(
			'category_name' => 'featured',
			'posts_per_page' => 3,
		); 
	
		$the_query = new WP_Query( $args );

		echo $before_widget;
		?>
		
		<div class="bs-widget-pack post-slider-widget">
		
			<?php if($instance['title']) { ?>
				<header><h3><?=$instance['title']?></h3></header>
			<?php } ?>
				<div class="post-slider">
					<ul class="slides">
					
					<?php if ( $the_query->have_posts() ) : ?>
	  				<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	  					<li>
	 							<a href="<?= the_permalink() ?>">
		  						<?php if ( get_the_post_thumbnail() != '' ) { ?>
	   								<?php the_post_thumbnail('small'); ?>
									<?php } else { ?>
		   								<img src="<?= catch_that_image(); ?>" >
								<?php } ?>
								</a>
		    				<?php the_title('<h2>', '</h2>'); ?>
		    				<?php the_excerpt('<p>', '</p>'); ?>
	    				</li>
	  				<?php endwhile; ?>
	  				<?php wp_reset_postdata(); ?>
					<?php else:  ?>
	  					<p>
	  						<?php _e( 'Sorry, no posts matched your criteria.' ); ?>
	  					</p>
					<?php endif; ?>
					</ul>
				</div>
			</div>
		<?php

		echo $after_widget;
	}

}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateDirectoryPostsSlider");'));