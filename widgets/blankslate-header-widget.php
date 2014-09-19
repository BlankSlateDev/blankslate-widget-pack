<?php
/*
Widget Name: BlankSlate Header Widget
Description: Display Header and Link to Pages
Author: Blankslate (DH)
Version: 1.0.0
*/

class BlankSlateHeaderWidget extends WP_Widget {

	function BlankSlateHeaderWidget() {
		$widget_ops = array(
			'classname' => 'BlankSlateHeaderWidget', 
			'description' => 'Display Header and Link to Pages' );
			
		$this->WP_Widget('BlankSlateHeaderWidget', 'BlankSlate Header Widget', $widget_ops);
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

<div class="bs-widget-pack header-widget">
	<h1><?= get_the_title(); ?></h1>
	<a class="promote-button" href="/<?=BLANKSLATE_BASEURL.'/join/'?>">Promote Your Business<i class="icon-chevron-right"></i></a>
	<p></p>
</div>
		
		<?php echo $after_widget;

	}

}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateHeaderWidget");'));