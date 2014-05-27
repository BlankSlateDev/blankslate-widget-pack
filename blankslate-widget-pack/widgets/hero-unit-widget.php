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
		 
		echo $before_widget; ?>


		<h1><?= $title ?></h1>
		
		
		<?php echo $after_widget;

	}

}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateHeroUnitWidget");'));