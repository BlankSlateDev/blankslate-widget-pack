<?php
/*
Plugin Name: BlankSlate Directory Pattern Loop
Description: Displays pattern of businesses
Author: Blankslate (DH)
Version: 1.0.0
*/

class BlankSlateDirectoryPatternLoop extends WP_Widget {
	function BlankSlateDirectoryPatternLoop() {
		$widget_ops = array(
			'classname' => 'BlankSlateDirectoryPatternLoop', 
			'description' => 'Displays pattern of businesses' );

		$this->WP_Widget('BlankSlateDirectoryPatternLoop', 'BlankSlate Directory Pattern Loop', $widget_ops);
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
		  <label for="<?=$this->get_field_id('seeMore'); ?>">See More Link:
			<input class="widefat" id="<?=$this->get_field_id('seeMore'); ?>" name="<?=$this->get_field_name('seeMore'); ?>" type="text" value="<?=$instance['seeMore'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('numRows'); ?>"> Number of Rows: 
			<input class="widefat" id="<?=$this->get_field_id('numRows'); ?>" name="<?=$this->get_field_name('numRows'); ?>" type="text" value="<?=$instance['numRows'];?>" />
		  </label>
		</p>
		<p>
			<label for="<?= $this->get_field_id('startOn'); ?>"> What block to start pattern on: 
				<select name="<?= $this->get_field_name('startOn'); ?>" id="<?= $this->get_field_id('startOn'); ?>" class="widefat">
				<?php
					$blocks = array( 'One Large, Three Small', 'Six Small', 'Three Small, One Large', 'Two Large',  'Large Top, Small Bottom', 'Four Medium' ); 
					foreach ($blocks as $key => $block) { ?>
						<option 
							value="<?= $key ?>" 
							id="<?= $key ?>"
							<?php echo $instance['startOn'] == $key ? 'selected="selected"' : '' ?>
						>
								<?= $block ?>
						</option>
					<?php } ?>
				</select>
			</label>
		</p>
		<p>
			<label for="<?=$this->get_field_id('repeat'); ?>"> Repeat this block: </label>
			<select name="<?=$this->get_field_name('repeat'); ?>" id="<?=$this->get_field_id('repeat');?>">
				<option value="true" <?=($instance['repeat'] == 'true') ? ' selected="selected"' : '' ?>>Yes</option>
				<option value="false" <?=($instance['repeat'] == 'false') ? ' selected="selected"' : '' ?>>No</option>
			</select>
		</p>

		<?php
			$promotions = new Promotions();
			if( $promotions->call() === True ){
				$results = $promotions->getData();
				$levels = $results['data'];
			}
		?>

		<p>
			<label for="<?= $this->get_field_id('large_block'); ?>"> Promo level for Large Block:
				<select name="<?= $this->get_field_name('large_block'); ?>" id="<?= $this->get_field_id('large_block'); ?>" class="widefat">
					<?php $this->ShowLevels($levels, $instance['large_block']); ?>
				</select>
			</label>
		</p>

		<p>
			<label for="<?= $this->get_field_id('small_block'); ?>"> Promo level for Small Blocks:
				<select name="<?= $this->get_field_name('small_block'); ?>" id="<?= $this->get_field_id('small_block'); ?>" class="widefat">
					<?php $this->ShowLevels($levels, $instance['small_block']); ?>
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
		  <label for="<?=$this->get_field_id('address'); ?>">Result Content Score (0-99):
			<input class="widefat" id="<?=$this->get_field_id('content_score'); ?>" name="<?=$this->get_field_name('content_score'); ?>" type="text" value="<?=$instance['content_score'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('num_enhanced'); ?>"> Number of Enhanced: 
			<input class="widefat" id="<?=$this->get_field_id('num_enhanced'); ?>" name="<?=$this->get_field_name('num_enhanced'); ?>" type="text" value="<?=$instance['num_enhanced'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('num_basic'); ?>"> Number of Basic: 
			<input class="widefat" id="<?=$this->get_field_id('num_basic'); ?>" name="<?=$this->get_field_name('num_basic'); ?>" type="text" value="<?=$instance['num_basic'];?>" />
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
		$instance['numRows'] 				= esc_attr( strip_tags($new_instance['numRows']) );
		$instance['startOn'] 				= esc_attr( strip_tags($new_instance['startOn']) );
		$instance['repeat'] 				= esc_attr( strip_tags($new_instance['repeat']) );
		$instance['layout'] 				= esc_attr( strip_tags($new_instance['layout']) );
		$instance['small_block'] 		= esc_attr( strip_tags($new_instance['small_block']) );
		$instance['large_block'] 		= esc_attr( strip_tags($new_instance['large_block']) );
		$instance['address'] 				= esc_attr( strip_tags($new_instance['address']) );
		$instance['content_score'] 	= esc_attr( strip_tags($new_instance['content_score']) );
		$instance['sort_by'] 				= esc_attr( strip_tags($new_instance['sort_by']) );
		$instance['num_enhanced'] 	= esc_attr( strip_tags($new_instance['num_enhanced']) );
		$instance['num_basic'] 			= esc_attr( strip_tags($new_instance['num_basic']) );

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

		//Number of business rows to output
		$numRows = (!empty($instance['numRows'])) ? $instance['numRows'] : 4;

		//What level of pattern to start on
		$startOn = (!empty($instance['startOn'])) ? $instance['startOn'] : 0;

		//Repeat same part of pattern continously
		$repeat = $instance['repeat'];

		$sortBy = $instance['sort_by'];

		//What promotion levels the difference blocks should pull from
		$smallLevel = $instance['small_block'];
		$largeLevel = $instance['large_block'];

		$different = $smallLevel == $largeLevel ? false : true;

		$numEnhanced = $instance['num_enhanced'];
		$numBasic = $instance['num_basic'];

		echo $before_widget; ?>
			<div class="bs-widget-pack pattern-loop">
				<?php if($instance['title']): ?>
					<header>
						<h3><?= $instance['title'] ?>
						<?php if($seeMore){?>
							<!-- <a href="<?php echo $seeMore;?>" class="widget-see-more">
								<span class="content"> See More</span>
								<i class="icon-chevron-right"></i>
							</a> -->
						<?php } ?>
						</h3>
					</header>
				<?php endif; ?>

				<?php
					$patterns = array( 'loop_one_three', 'loop_six', 'loop_three_one', 'loop_two',  'loop_large_small', 'loop_four' );

					//Return arrays of businesses on different promotion levels
					$query = array();
					$query['cat'] = $categories;
					$query['promote_on'] = $smallLevel;
					
					$query['lat'] = $lat;
					$query['lng'] = $lng;
					$query['sort'] = $sortBy;
					if(!empty($numBasic)){
						$query['rp'] = $numBasic;
					}
					
					if(!empty($content_score) && $content_score > 0 ){
						$query['content_score'] = $content_score;
					}
					
					if ( !empty($query['promote_on']) ){ // If promotion selected, use Promotions API
						$promoted = new Promoted(null, $query);
						if( $promoted->call() === True ){
							$results = $promoted->getData();
							$businesses = $results['data'];
						}

						if ( $different ){
							$query = array();
							$query['promote_on'] = $largeLevel;
							$query['cat'] = $categories;

							$query['sort'] = $sortBy;
							
							if(!empty($numEnhanced)){
								$query['rp'] = $numEnhanced; 
							}

							$enhanced = new Promoted(null, $query);
							if ( $enhanced->call() === True ){
								$results = $enhanced->getData();
								$premium = $results['data'];
							}
						}

						//Loop through patterns
						$i = 0;
						$order = $startOn;
						
						if($patterns[$order] == 'loop_large_small'){
							while(current($premium)){
								loop_two(&$premium, array());
							}
							while(current($businesses)){
								loop_six(array(), &$businesses);
							}
						} else {
							while ($i < $numRows) {
								if ($order === count($patterns)){
									$order = 0;
								}
							
								if ( $different ){
									call_user_func_array($patterns[$order], array(&$premium, &$businesses));
								} else {
									call_user_func_array($patterns[$order], array(&$businesses, &$businesses));
								}
							
								$i += 1;
								if ($repeat === 'false'){
									$order += 1;
								}
							}
						}
					} else { //Use Search API
						
						$featured = new SearchResults(null, $query);

						if( $featured->call() === true ){
							$results = $featured->getData();
							$businesses = $results['data'];
						}

						$i = 0;
						$order = $startOn;

						while ($i < $numRows) {
							if ($order === count($patterns)){
								$order = 0;
							}
						
							call_user_func_array($patterns[$order], array(&$businesses, &$businesses));
						
							$i += 1;
							if ($repeat === 'false'){
								$order += 1;
							}
						}
					} ?>
				<?php if($seeMore){?>
					<a href="<?php echo $seeMore;?>" class="widget-see-more--bottom">
						<span class="content"> See More Places To Eat</span>
						<i class="icon-chevron-right"></i>
					</a>
				<?php } ?>
			</div>

		<?php echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateDirectoryPatternLoop");'));