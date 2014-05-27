<?php
/*
Plugin Name: BlankSlate Directory Blogroll
Description: Blogroll for Category Page in Directory Plugin
Author: Blankslate (DH)
Version: 1.0.0
*/

class BlankSlateDirectoryBlogrollWidget extends WP_Widget {

	function BlankSlateDirectoryBlogrollWidget() {
		$widget_ops = array(
			'classname' => 'BlankSlateDirectoryBlogrollWidget', 
			'description' => 'Blogroll for Category Page in Directory Plugin' );
			
		$this->WP_Widget('BlankSlateDirectoryBlogrollWidget', 'BlankSlate Directory Blogroll', $widget_ops);
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
		  <label for="<?=$this->get_field_id('featured'); ?>">Featured Post:
			<input class="widefat" id="<?=$this->get_field_id('featured'); ?>" name="<?=$this->get_field_name('featured'); ?>" type="text" value="<?=$instance['featured'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('secondary'); ?>">Category for Secondary Posts:
			<input class="widefat" id="<?=$this->get_field_id('secondary'); ?>" name="<?=$this->get_field_name('secondary'); ?>" type="text" value="<?=$instance['secondary'];?>" />
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
		$instance['featured'] = esc_attr( strip_tags($new_instance['featured']) );
		$instance['secondary'] = esc_attr( strip_tags($new_instance['secondary']) );
		
		$instance['layout'] = esc_attr( strip_tags($new_instance['layout']) );

		return $instance;
	}
	
	function widget($args, $instance) {
	
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$site_root_url = home_url();
		
		$layout = $instance['layout'];

		echo $before_widget;
		?>

		<?php
			$args = array(
				'posts_per_page' => 1,
				'name' => $instance['featured']
			);

			$query = new WP_Query( $args )
		?>

		<div class="bs-widget-pack directory-blogroll <?= $layout ?>">
			<header>
				<h3><?= $title ?></h3>
			</header>
			<div class="feature-post">
			<?php if( $query->have_posts() ): ?>
				<?php while( $query->have_posts() ): $query->the_post(); ?>
					
					<div class="image">
						<a href="<?= the_permalink() ?>">
						<?php the_post_thumbnail('small'); ?>
						</a>
					</div>
					<div class="content">
						<?php the_title('<h2>','</h2>'); ?>
						<?php the_excerpt(); ?>
					</div>
					
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
			</div>

			<?php
			$args = array(
				'posts_per_page' => 3,
				'category_name' => $instance['secondary']
			);

			$query = new WP_Query( $args )
		?>

			<div class="secondary-posts">
				<ul>
				<?php if( $query->have_posts() ): ?>
					<?php while( $query->have_posts() ): $query->the_post(); ?>
					
						<li>
							<div class="image">
								<a href="<?= the_permalink() ?>">
								<?php the_post_thumbnail('small'); ?>
								</a>
							</div>
							<div class="content">
								<?php the_title('<h2>','</h2>'); ?>
								<?php the_excerpt(); ?>
							</div>
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

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateDirectoryBlogrollWidget");'));