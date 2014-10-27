<?php
/*
Plugin Name: BlankSlate Directory Tiled Businesses
Description: Displays tiled pattern of businesses
Author: Blankslate (DH)
Version: 1.0.1
*/

class BlankSlateDirectoryTiledBusinesses extends WP_Widget {
	function BlankSlateDirectoryTiledBusinesses() {
		$widget_ops = array(
			'classname' => 'BlankSlateDirectoryTiledBusinesses', 
			'description' => 'Displays tiled pattern of businesses' );

		$this->WP_Widget('BlankSlateDirectoryTiledBusinesses', 'BlankSlate Directory Tiled Businesses', $widget_ops);
	}
	
	function ShowLevels($levels, $selected){ ?>
		<option value="" id="all-levels" <?php echo empty($selected) ? 'selected="selected"' : '' ?>>All</option> 
		<?php
		foreach ($levels as $level) { ?>
			<option value="<?= $level['key'] ?>" id="<?= $level['key'] ?>" <?php echo $selected == $level['key'] ? 'selected="selected"' : '' ?>>
				<?= $level['name'] ?>
			</option>
		<?php } 
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
		  <label for="<?=$this->get_field_id('seeMoreText'); ?>">See More Text:
			<input class="widefat" id="<?=$this->get_field_id('seeMoreText'); ?>" name="<?=$this->get_field_name('seeMoreText'); ?>" type="text" value="<?=$instance['seeMoreText'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('seeMore'); ?>">See More Link:
			<input class="widefat" id="<?=$this->get_field_id('seeMore'); ?>" name="<?=$this->get_field_name('seeMore'); ?>" type="text" value="<?=$instance['seeMore'];?>" />
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
			<label for="<?= $this->get_field_id('promo_level'); ?>"> Promo level:
				<select name="<?= $this->get_field_name('promo_level'); ?>" id="<?= $this->get_field_id('promo_level'); ?>" class="widefat">
					<?php $this->ShowLevels($levels, $instance['promo_level']); ?>
				</select>
			</label>
		</p>

		<p>
			<label for="<?= $this->get_field_id('sort_by'); ?>"> Sort Business Results By:
				<select name="<?= $this->get_field_name('sort_by'); ?>" id="<?= $this->get_field_id('sort_by'); ?>" class="widefat">
						<option value="random" 		id="random" 	<?php echo $instance['sort_by'] == 'random' 	? 'selected="selected"' : '' ?>> Random	 </option>
						<option value="distance" 	id="distance" <?php echo $instance['sort_by'] == 'distance' ? 'selected="selected"' : '' ?>> Distance </option>
						<option value="name" 			id="none" 		<?php echo $instance['sort_by'] == 'name' 		? 'selected="selected"' : '' ?>> Name		 </option>
						<option value="content" 	id="content" 	<?php echo $instance['sort_by'] == 'content' 	? 'selected="selected"' : '' ?>> Content	 </option>
						<option value="" 					id="none" 		<?php echo $instance['sort_by'] == '' 				? 'selected="selected"' : '' ?>> Default	 </option>
				</select>
			</label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('address'); ?>">Exact Address:
			<input class="widefat" id="<?=$this->get_field_id('address'); ?>" name="<?=$this->get_field_name('address'); ?>" type="text" value="<?=$instance['address'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('content_score'); ?>">Result Content Score (0-99):
			<input class="widefat" id="<?=$this->get_field_id('content_score'); ?>" name="<?=$this->get_field_name('content_score'); ?>" type="text" value="<?=$instance['content_score'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('business_count'); ?>"> Max number to display: 
			<input class="widefat" id="<?=$this->get_field_id('business_count'); ?>" name="<?=$this->get_field_name('business_count'); ?>" type="text" value="<?=$instance['business_count'];?>" />
		  </label>
		</p>

		<p>
			<label>Categories: 
			<?php
				
				$ls_cat_tree = blankslate_get_categories();
				$cat_html = blankslate_print_widget_cats( $ls_cat_tree[0]['children'], 'none', $instance, 0, $this );
				echo $cat_html;

			?>
			<a href="javascript:(void);" class="ls-expand-cats">Expand All</a> 
		</p>

		<script type="text/javascript">
			(function(jQuery){
				jQuery('.expand').live('click', function(e){
					e.preventDefault();
					e.stopPropagation();
					jQuery(this).parent().next('ul').toggle();
				});
			}(jQuery));
		</script>

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
		$instance['title'] 					= esc_attr( strip_tags($new_instance['title']) );
		$instance['seeMore'] 				= esc_attr( strip_tags($new_instance['seeMore']) );
		$instance['seeMoreText'] 				= esc_attr( strip_tags($new_instance['seeMoreText']) );
		$instance['promo_level'] 				= esc_attr( strip_tags($new_instance['promo_level']) );
		$instance['address'] 				= esc_attr( strip_tags($new_instance['address']) );
		$instance['content_score'] 	= esc_attr( strip_tags($new_instance['content_score']) );
		$instance['sort_by'] 				= esc_attr( strip_tags($new_instance['sort_by']) );
		$instance['business_count'] 	= esc_attr( strip_tags($new_instance['business_count']) );

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
		
		$seeMore = $instance['seeMore'];
		if ( $instance['seeMoreText'] ){
			$seeMoreText = $instance['seeMoreText'];
		} else {
			$seeMoreText = 'See More';
		}
		
		$site_root_url = home_url();
		$address = $instance['address'];
		if ($address) {
			$addressArray = blankslate_get_lat_lng($address);
			$lat = $addressArray['lat'];
			$lng = $addressArray['lon'];
		}
		
		$content_score = $instance['content_score'];

		$categories = '';
		foreach ($instance as $key => $value) {
			if ($value == 'on'){
				$categories .= slugify($key) . ',';
			}
		}
		$categories = rtrim($categories, ',');

		$sortBy = $instance['sort_by'];

		echo $before_widget; ?>
			
			<div class="bs-widget-pack tile-widget">
				<?php if($instance['title']): ?>
					<header>
						<h3><?= $instance['title'] ?>
						</h3>
					</header>
				<?php endif; ?>

				<?php

					//Return arrays of businesses on different promotion levels
					$query = array();
					$query['cat'] = $categories;
					$query['promote_on'] = $instance['promo_level'];
					if( isset($lat) && isset($lng) ){
						$query['lat'] = $lat;
						$query['lng'] = $lng;
					}
					$query['sort'] = $sortBy;
					$query['rp'] = $instance['business_count'];

					if(!empty($content_score) && $content_score > 0 && !$instance['promo_level']){
						$query['content_score'] = $content_score;
					}
					// If promotion selected, use Promotions API
					// Else, use Search API
					if ( !empty($query['promote_on']) ){ 
						$promoted = new Promoted(null, $query);
						if( $promoted->call() === True ){
							$results = $promoted->getData();
							$businesses = $results['data'];
						}

						echo '<div id="blankslate-tile-container">';
						echo '<div class="gutter-sizer"></div>';

						foreach( $businesses as $business ){
							include(BLANKSLATE_WIDGET_PACK_DIR.'/templates/_tile.php');
						}

						echo '</div>';?>
						
						<?php
					} else { 
						$featured = new SearchResults(null, $query);
						if( $featured->call() === true ){
							$results = $featured->getData();
							$businesses = $results['data'];
						}

						echo '<div id="blankslate-tile-container">';
						foreach( $businesses as $business ){
							include(BLANKSLATE_WIDGET_PACK_DIR.'/templates/_tile.php');
						}
						
						echo '</div>';
					} ?>
				<?php if($seeMore && $seeMoreText ){?>
					<a href="<?php echo $seeMore;?>" class="widget-see-more--bottom">
						<span class="content"><?= $seeMoreText ?></span>
						<i class="icon-chevron-right"></i>
					</a>
				<?php } ?>
			</div>
			<script>
				jQuery(document).ready(function(){

					//override lazy load
					var lazy = function( img ){
						var $img = jQuery( img ),
								src = $img.attr( 'data-lazy-src' );

						$img.unbind( 'scrollin' ) // remove event binding
							.hide()
							.removeAttr( 'data-lazy-src' )
							.attr( 'data-lazy-loaded', 'true' );

						img.src = src;
						$img.fadeIn();
					};

					var $container = jQuery('#blankslate-tile-container');

					//jQuery('.blankslate-tile').hide();

					jQuery('.blankslate-tile img[data-lazy-src]').each( function(){
						lazy( this );
					});

					// initialize
					$container.imagesLoaded( function() {
						//jQuery('.blankslate-tile').fadeIn();
						$container.masonry({
							columnWidth: '.blankslate-tile',
							itemSelector: '.blankslate-tile'
						});
					});
				});
			</script>

		<?php echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateDirectoryTiledBusinesses");'));