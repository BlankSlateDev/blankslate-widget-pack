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
		if ( empty( $instance ) ) {
			global $post;
			$all_instances = get_post_meta( $post->ID, 'panels_data', true);
			foreach( $all_instances['widgets'] as $i => $instances ) {
				if ( $this->panel_id == $i ){
					$instance = $instances;
				}
			}
		}

		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) ); 
		?>

		<p>
			<label for="<?=$this->get_field_id('title'); ?>">Title:
			<input class="widefat" id="<?=$this->get_field_id('title'); ?>" name="<?=$this->get_field_name('title'); ?>" type="text" value="<?=$instance['title'];?>" />
			</label>
		</p>
	
		<p>
			<label for="<?=$this->get_field_id('num_posts'); ?>"> Number of Posts to Display:
			<input class="widefat" id="<?=$this->get_field_id('num_posts'); ?>" name="<?=$this->get_field_name('num_posts'); ?>" type="text" value="<?=$instance['num_posts'];?>" />
			</label>
		</p>

		<p>
			<label for="<?=$this->get_field_id('featured'); ?>">Sticky Featured Post:
			<input class="widefat" 
						 id="<?=$this->get_field_id('featured'); ?>" 
						 name="<?=$this->get_field_name('featured'); ?>" 
						 type="text" 
						 value="<?=$instance['featured'];?>" />
			</label>
		</p>
		<p>
			<label for="<?=$this->get_field_id('secondary'); ?>">Post Category:
				<?php 
					wp_dropdown_categories(
						array(
							'class' 						=> 'widefat',
							'hide_empty' 				=> 0,
							'name' 							=> $this->get_field_name('secondary_cat'),
							'orderby' 					=> 'name',
							'selected' 					=> $instance['secondary_cat'],
							'hierarchical' 			=> true,
							'depth' 						=> 2,
							'show_option_none' 	=> __('None')
						));
				?>
		  </label>
		</p>
		<p>
			<label for="<?=$this->get_field_id('layout'); ?>"> Large image on right or left: </label>
			<select name="<?=$this->get_field_name('layout'); ?>" id="<?=$this->get_field_id('layout');?>">
				<option value="left" <?=($instance['layout'] == 'left') ? ' selected="selected"' : '' ?>>Left</option>
				<option value="right" <?=($instance['layout'] == 'right') ? ' selected="selected"' : '' ?>>Right</option>
			</select>
		</p>
		<?php
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = esc_attr( strip_tags($new_instance['title']) );
		$instance['num_posts'] = esc_attr( strip_tags($new_instance['num_posts']) );

		$instance['featured'] = esc_attr( strip_tags($new_instance['featured']) );
		$instance['secondary'] = esc_attr( strip_tags($new_instance['secondary_cat']) );

		$instance['layout'] = esc_attr( strip_tags($new_instance['layout']) );

		return $instance;
	}
	
	function widget($args, $instance) {
	
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$site_root_url = home_url();
		
		$layout = $instance['layout'];

		$num_posts = $instance['num_posts'];
		if ( empty($num_posts) || !isset($num_posts) || is_null($num_posts) ){
			$num_posts = 4;
		}

		echo $before_widget;
		?>
		<style type="text/css">

		</style>

		<div class="bs-widget-pack directory-blogroll <?= $layout ?>">
			<header>
				<h3><?= $title ?></h3>
			</header>
			<ul>
				
			<?php if ( !empty($instance['featured']) && isset($instance['featured']) && !is_null($instance['featured']) ){ ?>
			<?php
				$args = array(
					'posts_per_page' => 1,
					'name' => $instance['featured']
				);
				$query = new WP_Query( $args )
			?>
				<li>
				<?php if( $query->have_posts() ): ?>
					<?php while( $query->have_posts() ): $query->the_post(); ?>
						<div class="image">
							<a href="<?= the_permalink() ?>">
								<img src="<?= catch_that_image(); ?>" >
							</a>
						</div>
						<div class="content">
							<?php the_title('<h2>','</h2>'); ?>
							<?php the_excerpt(); ?>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php endif; ?>
				</li>
				<?php $num_posts -= 1; ?>
			<?php } ?>

			<?php
			wp_reset_postdata();
			$args2 = array(
				'category__in' => array($instance['secondary_cat']),
				'posts_per_page' => $num_posts 
			);
			
			$query2 = new WP_Query( $args2 );
		?>

			<?php if( $query2->have_posts() ): ?>
				<?php while( $query2->have_posts() ): $query2->the_post(); ?>
					<li>
						<div class="image">							
							<a href="<?= the_permalink() ?>">
								<img src="<?=BLANKSLATE_DIRECTORY_URL?>scripts/timthumb.php?w=600&h=480&zc=1&src=<?= catch_that_image(); ?>" >
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
		</div>

		<?php

		echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateDirectoryBlogrollWidget");'));