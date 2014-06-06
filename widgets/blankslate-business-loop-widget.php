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

/*
*		Form
**/
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		?>
		<script type="text/javascript">
			(function(){
				jQuery('.expand').live('click', function(){
					jQuery(this).parent().next('ul').toggle();
				});
			}());
		</script>

		<style type="text/css">
			ul.cat_list {
				margin-left:25px;
				display:none;
			}

			ul.cat_list li {
				padding:0px 8px;
				border:1px solid #EEE;
				padding-left:0;
			}

			ul.cat_list li span {
				padding:0px;
				display:block;
				float:left;
				text-align:center;
				width:20px;
				background:#EEE;
				margin-right:10px;
				cursor:pointer;
				color:#EEE;
			}

			ul.cat_list li span.expand {
				color:#000;
			}

			ul.cat_list.level-0 {
				display:block;
				margin-left:0px;
			}
		</style>
		<p>
		  <label for="<?=$this->get_field_id('title'); ?>">Title:
			<input class="widefat" id="<?=$this->get_field_id('title'); ?>" name="<?=$this->get_field_name('title'); ?>" type="text" value="<?=$instance['title'];?>" />
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
		<p>
		<p>

			<label>Categories: 
			<?php
				
				$ls_cat_tree = blankslate_get_categories();
				$cat_html = blankslate_print_widget_cats( $ls_cat_tree[0]['children'], 'none', $instance, 0, $this );
				echo $cat_html;

			?>
			<a href="javascript:(void);" class="ls-expand-cats">Expand All</a> 
		</p>

		<?php
	}

/*
*		Update
**/	
	function update($new_instance, $old_instance) {
		$categories = blankslate_get_raw_categories();
		$fields = array();
		foreach ($categories as $cat) {
			array_push($fields, $cat['name'] );
		}

		$instance = $old_instance;
		$instance['title'] = esc_attr( strip_tags($new_instance['title']) );
		$instance['address'] = esc_attr( strip_tags($new_instance['address']) );
		$instance['keys'] = esc_attr( strip_tags($new_instance['keys']) );

		foreach ($fields as $field) {
			$instance[$field] = $new_instance[$field];
		}

		return $instance;
	}
	
/*
*		The Widget
**/
	function widget($args, $instance) {
	
		global $post;
	
		extract( $args );
		
		global $default_results, $default_search, $hide_location_search, $detect_location, $default_location, $near, $q, $cat, $featured_tier;
		$title = apply_filters( 'widget_title', $instance['title'] );
		$site_root_url = home_url();

		$keys = $instance['keys'];
		$address = $instance['address'];
		$categories = '';
		
		foreach ($instance as $key => $value) {
			if ($value == 'on'){
				$categories .= slugify($key) . ',';
			}
		}

		$categories = rtrim($categories, ',');

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
			$queryParameters['cat'] = $categories;
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