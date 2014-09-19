<?php
/*
Plugin Name: BlankSlate Directory Showcase Widget
Description: Showcase six businesses in rows of three
Author: Blankslate (DH)
Version: 1.0.0
*/

class BlankSlateDirectoryShowcase extends WP_Widget {
	function BlankSlateDirectoryShowcase() {
		$widget_ops = array(
			'classname' => 'BlankSlateDirectoryShowcase', 
			'description' => 'Showcase businesses in rows of three' );

		$this->WP_Widget('BlankSlateDirectoryShowcase', 'BlankSlate Directory Showcase Widget', $widget_ops);
	}

/*
*		Form
**/
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		?>
		<p>
		  <label for="<?=$this->get_field_id('title'); ?>">Title:
			<input class="widefat" id="<?=$this->get_field_id('title'); ?>" name="<?=$this->get_field_name('title'); ?>" type="text" value="<?=$instance['title'];?>" />
		  </label>
		</p>
		<h5>Keys</h5>
		<p>
		  <label for="<?=$this->get_field_id('key-1'); ?>">Key 1:
			<input class="widefat" id="<?=$this->get_field_id('key-1'); ?>" name="<?=$this->get_field_name('key-1'); ?>" type="text" value="<?=$instance['key-1'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('key-2'); ?>">Key 2:
			<input class="widefat" id="<?=$this->get_field_id('key-2'); ?>" name="<?=$this->get_field_name('key-2'); ?>" type="text" value="<?=$instance['key-2'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('key-3'); ?>">Key 3:
			<input class="widefat" id="<?=$this->get_field_id('key-3'); ?>" name="<?=$this->get_field_name('key-3'); ?>" type="text" value="<?=$instance['key-3'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('key-4'); ?>">Key 4:
			<input class="widefat" id="<?=$this->get_field_id('key-4'); ?>" name="<?=$this->get_field_name('key-4'); ?>" type="text" value="<?=$instance['key-4'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('key-5'); ?>">Key 5:
			<input class="widefat" id="<?=$this->get_field_id('key-5'); ?>" name="<?=$this->get_field_name('key-5'); ?>" type="text" value="<?=$instance['key-5'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('key-6'); ?>">Key 6:
			<input class="widefat" id="<?=$this->get_field_id('key-6'); ?>" name="<?=$this->get_field_name('key-6'); ?>" type="text" value="<?=$instance['key-6'];?>" />
		  </label>
		</p>

		<?php
	}

/*
*		Update
**/	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = esc_attr( strip_tags($new_instance['title']) );

		$instance['key-1'] = esc_attr( strip_tags($new_instance['key-1']) );
		$instance['key-2'] = esc_attr( strip_tags($new_instance['key-2']) );
		$instance['key-3'] = esc_attr( strip_tags($new_instance['key-3']) );
		$instance['key-4'] = esc_attr( strip_tags($new_instance['key-4']) );
		$instance['key-5'] = esc_attr( strip_tags($new_instance['key-5']) );
		$instance['key-6'] = esc_attr( strip_tags($new_instance['key-6']) );

		return $instance;
	}
	
/*
*		The Widget
**/
	function widget($args, $instance) {
	
		extract( $args );
		
		$title = apply_filters( 'widget_title', $instance['title'] );

		$key_array = array(
			$instance['key-1'],
			$instance['key-2'],
			$instance['key-3'],
			$instance['key-4'],
			$instance['key-5'],
			$instance['key-6']
		);

		$key_string = implode(',', $key_array);

		//If first key is filled out, query by keys
		if (!empty($instance['key-1'])) {
			$query = array();
			$query['keys'] = $key_string;
			$query['rp'] = 6;
			$query['sort']='name';
			$featured = new SearchResults(null, $query);
			if( $featured->call() === True ){
				$results = $featured->getData();
				$businesses = $results['data'];
			} 
		//otherwise, grab from brownstoner enhanced

		} else {
			$query = array();
			
			$query['rp'] = 6;
			$enhanced = new Promoted(null, $query);
			if ( $enhanced->call() === True ){
				$results = $enhanced->getData();
				$businesses = $results['data'];
			}
		}

		echo $before_widget; ?>

		<div class="bs-widget-pack showcase-widget">
			<?php if($instance['title']): ?>
				<header>
					<h3><?= $instance['title'] ?></h3>
				</header>
			<?php endif; ?>

			<?php for ($i = 0; $i < 6; $i ++){
					$business = current($businesses);
					next($businesses);
					include(BLANKSLATE_WIDGET_PACK_DIR.'/templates/_showcase.php');
				}
			?>
		</div>

		<script type="text/javascript">
			(function(){
			}());
		</script>

		<?php echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateDirectoryShowcase");')); 