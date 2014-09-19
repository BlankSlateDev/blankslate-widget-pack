<?php
/*
Plugin Name: BlankSlate Directory Sidebar Businesses
Description: Displays businesses in a by default 320px width container
Author: Blankslate (DH)
Version: 1.0.0
*/

class BlankSlateDirectorySidebarBusinesses extends WP_Widget {
	function BlankSlateDirectorySidebarBusinesses() {
		$widget_ops = array(
			'classname' => 'BlankSlateDirectorySidebarBusinesses', 
			'description' => 'Displays businesses in a by default 320px width container' );

		$this->WP_Widget('BlankSlateDirectorySidebarBusinesses', 'BlankSlate Directory Sidebar Businesses', $widget_ops);
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
		<p>
		  <label for="<?=$this->get_field_id('url'); ?>">Link for Title:
			<input class="widefat" id="<?=$this->get_field_id('url'); ?>" name="<?=$this->get_field_name('url'); ?>" type="text" value="<?=$instance['url'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('numRows'); ?>"> Number of Rows: 
			<input class="widefat" id="<?=$this->get_field_id('numRows'); ?>" name="<?=$this->get_field_name('numRows'); ?>" type="text" value="<?=$instance['numRows'];?>" />
		  </label>
		</p>

		<?php
			$promotions = new Promotions();
			if( $promotions->call() === True ){
				$results = $promotions->getData();
				$levels = $results['data'];
			}
		?>

		<p>
			<label for="<?= $this->get_field_id('promo_level'); ?>"> Promotion Level:
				<select name="<?= $this->get_field_name('promo_level'); ?>" id="<?= $this->get_field_id('promo_level'); ?>" class="widefat">
				<?php
					foreach ($levels as $level) { ?>
						<option 
							value="<?= $level['key'] ?>" 
							id="<?= $level['key'] ?>"
							<?php echo $instance['promo_level'] == $level['key'] ? 'selected="selected"' : '' ?>
						>
								<?= $level['name'] ?>
						</option>
					<?php } ?>
				</select>
			</label>
		</p>


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
		$instance['url'] = $new_instance['url'];
		$instance['numRows'] = esc_attr( strip_tags($new_instance['numRows']) );
		$instance['promo_level'] = esc_attr( strip_tags($new_instance['promo_level']) );

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

		$categories = '';
		foreach ($instance as $key => $value) {
			if ($value == 'on'){
				$categories .= slugify($key) . ',';
			}
		}
		$categories = rtrim($categories, ',');

		//Number of business rows to output
		$numRows = (!empty($instance['numRows'])) ? $instance['numRows'] : 2;

		//Repeat same part of pattern continously
		$repeat = $instance['repeat'];

		$promoLevel = $instance['promo_level'];

		echo $before_widget; ?>

			<div class="bs-widget-pack sidebar-businesses">
				<?php if($instance['title']): ?>
					<header>
						<?php if ( $instance['url'] ){ ?>
						<h3><a href="<?= $instance['url'] ?>"><?= $instance['title'] ?></a></h3>
						<?php } else { ?>
						<h3><?= $instance['title'] ?></h3>
						<?php } ?>
					</header>
				<?php endif; ?>

				<?php
					//Return arrays of businesses on different promotion levels
					$query = array();
					$query['cat'] = $categories;
					$query['promote_on'] = $promoLevel;
					$query['rp'] = $numRows * 2;
					$query['sort'] = 'random';
					$promoted = new Promoted(null, $query);
					if( $promoted->call() === True ){
						$results = $promoted->getData();
						$businesses = $results['data'];
					}
				?>

				<?php for ($i = 0; $i < ($numRows * 2); $i ++){
					$business = current($businesses);
					next($businesses);
					include(BLANKSLATE_WIDGET_PACK_DIR.'/templates/_sidebar.php');
				} ?>
			</div>

		<?php echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateDirectorySidebarBusinesses");'));