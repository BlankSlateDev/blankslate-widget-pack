<?php
/*
Plugin Name: Blankslate Neighborhood Slider Widget
Description: Display Neighhorhoods in a slider
Author: Blankslate (DH)
Version: 1.0.0
*/

class BlankSlateNeighborhoodSliderWidget extends WP_Widget {

	function BlankSlateNeighborhoodSliderWidget() {
		$widget_ops = array(
			'classname' => 'BlankSlateNeighborhoodSliderWidget', 
			'description' => 'Display Neighborhoods in a Slider' );
			
		$this->WP_Widget('BlankSlateNeighborhoodSliderWidget', 'BlankSlate Neighborhood Slider', $widget_ops);
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
	
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$site_root_url = home_url();

		echo $before_widget;


		?>

		<div class="bs-widget-pack neighborhood-slider">
			<?php if($instance['title']) { ?>
				<header><h3><?=$instance['title']?></h3></header>
			<?php } ?>
				<div class="flexslider">
					<ul class="slides">
						<?php
								$args = array(
									'post_type'   => 'bsd_Neighborhood',
									'order'               => 'DESC',
									'posts_per_page'         => -1,
								);
								$query = new WP_Query( $args );
						?>

						<?php if( $query->have_posts() ):?>
							<?php while( $query->have_posts() ): $query->the_post(); ?>
								<?php
									$attachment_id = get_post_thumbnail_id( get_the_ID() );
									if ( $attachment_id ){
										$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
									}

									if ($image_attributes){
										$img = $image_attributes[0];
									}

									$src = '/wp-content/themes/incontext/timthumb.php?w=280&h=150&zc=1&src=' . $img . '';

								?>


								<li>
									<a href="<?= the_permalink() ?>">
									<img src="<?= $src ?>" />
									</a>
									<?php the_title('<h5>','</h5>'); ?>
									
								</li>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						<?php endif; ?>

					</ul>
				</div>
		</div>
		
		<?php

		echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateNeighborhoodSliderWidget");'));