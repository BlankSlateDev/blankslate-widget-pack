<?php
/*
Widget Name: Blankslate Hero Unit Widget
Description: Part of BlankSlate Widgets Pack
Author: Blankslate (DH)
Version: 1.0.0
*/

class BlankSlateHeroUnitWidget extends WP_Widget {

	function BlankSlateHeroUnitWidget() {
		$widget_ops = array(
			'classname' => 'BlankSlateHeroUnitWidget', 
			'description' => 'Part of BlankSlate Widgets Pack' );
			
		$this->WP_Widget('BlankSlateHeroUnitWidget', 'BlankSlate Hero Unit Widget', $widget_ops);
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		?>
		<p>
		  <label for="<?=$this->get_field_id('title'); ?>">Title:
			<input 
				class="widefat" 
				id="<?=$this->get_field_id('title'); ?>" 
				name="<?=$this->get_field_name('title'); ?>" 
				type="text" 
				value="<?=$instance['title'];?>"
			/>
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('banner_text'); ?>">Banner Text:
			<input 
				class="widefat" 
				id="<?=$this->get_field_id('banner_text'); ?>" 
				name="<?=$this->get_field_name('banner_text'); ?>" 
				type="text" 
				value="<?=$instance['banner_text'];?>"
			/>
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('subtext'); ?>">Title Subtext:
			<input 
				class="widefat" 
				id="<?=$this->get_field_id('subtext'); ?>" 
				name="<?=$this->get_field_name('subtext'); ?>" 
				type="text" 
				value="<?=$instance['subtext'];?>"
			/>
		  </label>
		</p>
		
		<?php
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = esc_attr( strip_tags($new_instance['title']) );
		$instance['banner_text'] = esc_attr( strip_tags($new_instance['banner_text']) );
		$instance['subtext'] = esc_attr( strip_tags($new_instance['subtext']) );

		return $instance;
	}
	
	function widget($args, $instance) {
	
		extract( $args );
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		$banner_text = $instance['banner_text'];
		$subtext = $instance['subtext'];

		echo $before_widget; ?>

		<section class="bs-widget-pack blankslate-hero-unit">
			<header>
				<div class="hero-image">
					<div class="image-tag"><?= $banner_text ?></div>
					<?php

					$attachment_id = get_post_thumbnail_id( get_the_ID() );
					$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );

					if( $image_attributes ){
						$img = $image_attributes[0];
					}

					$src = '/wp-content/themes/incontext/timthumb.php?w=1024&h=500&zc=1&src=' . $img . '';
					?>

					<img src="<?= $src ?>" />
					<div class="title-hold">
						<h1><?= get_the_title(); ?> </h1>
						<h2><?= $subtext ?></h2>
					</div>
				</div>
			</header>
			<div class="body-content">
			</div>
		</section>

		<?php echo $after_widget;

	}

}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateHeroUnitWidget");'));
