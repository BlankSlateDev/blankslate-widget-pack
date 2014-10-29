<?php
/*
Plugin Name: BlankSlate Directory Pattern Loop
Description: Displays pattern of businesses
Author: Blankslate (DH)
Version: 1.0.1
*/

class BlankSlateDirectoryPatternLoop extends WP_Widget {
	function BlankSlateDirectoryPatternLoop() {
		$widget_ops = array(
			'classname' => 'BlankSlateDirectoryPatternLoop', 
			'description' => 'Displays pattern of businesses' );

		$this->WP_Widget('BlankSlateDirectoryPatternLoop', 'BlankSlate Directory Pattern Loop', $widget_ops);
	}
	
	function ShowLevels($levels, $selected){ ?>
		<option value="" id="all-levels" <?php echo empty($selected) ? 'selected="selected"' : '' ?>>Any Level</option> 
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
		<style type="text/css">
			p {
				float: left;
				width: 100%;
				padding-right: 20px;
			}

			h2 {
				margin-top: 12px;
				clear: both;
			}

			.configure-display,
			.select-businesses,
			.search-params {
				overflow: auto;
			}

			.configure-display *,
			.select-businesses *,
			.search-params * {
				box-sizing: border-box;
			}

			.configure-display {
				clear: both;
			}

			.configure-display p {
				width:20%;
				float: left;
				margin-right: 20px;
			}

			.half-width {
				float: left;
				width: 50%;
				padding-right: 20px;
				box-sizing: border-box;
			}

			.select-businesses {
				clear: both;
				width: 30%;
				float: left;
			}

			.search-params {
				float: left;
				width: 70%;
			}

			.keys input {
				float: left;
				width: 80%;
			}

			.keys .dashicons-no {
				float: left;
				color: rgb(236,29,35);
			}

		</style>
		<h2>Widget Header</h2>
		<p>
		  <label for="<?=$this->get_field_id('title'); ?>">Title:
			<input class="widefat" id="<?=$this->get_field_id('title'); ?>" name="<?=$this->get_field_name('title'); ?>" type="text" value="<?=$instance['title'];?>" />
		  </label>
		</p>
		<p class="half-width">
		  <label for="<?=$this->get_field_id('seeMoreText'); ?>">See More link text:
			<input class="widefat" id="<?=$this->get_field_id('seeMoreText'); ?>" name="<?=$this->get_field_name('seeMoreText'); ?>" type="text" value="<?=$instance['seeMoreText'];?>" />
		  </label>
		</p>
		<p class="half-width">
		  <label for="<?=$this->get_field_id('seeMore'); ?>">See More link URL:
			<input class="widefat" id="<?=$this->get_field_id('seeMore'); ?>" name="<?=$this->get_field_name('seeMore'); ?>" type="text" value="<?=$instance['seeMore'];?>" />
		  </label>
		</p>

		<div class="configure-display">
		<h2>Configure display</h2>
		<p>
			<label for="<?= $this->get_field_id('startOn'); ?>"> Display pattern: 
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
		  <label for="<?=$this->get_field_id('numRows'); ?>"> Total rows to display
			<input class="widefat" id="<?=$this->get_field_id('numRows'); ?>" name="<?=$this->get_field_name('numRows'); ?>" type="text" value="<?=$instance['numRows'];?>" />
		  </label>
		</p>
		<p>
			<label for="<?=$this->get_field_id('repeat'); ?>"> Pattern to display after first row </label>
			<select name="<?=$this->get_field_name('repeat'); ?>" id="<?=$this->get_field_id('repeat');?>">
				<option value="true" <?=($instance['repeat'] == 'true') ? ' selected="selected"' : '' ?>>Same pattern</option>
				<option value="false" <?=($instance['repeat'] == 'false') ? ' selected="selected"' : '' ?>>Next pattern</option>
			</select>
		</p>
		</div>
		
		<h2>Select Businesses to include</h2>

		<div class="select-businesses">
			<h3>Pick specific businesses (enter 14 digit ID)</h3>
			<div class="keys">
				<?php 
					//Outputs number of text inputs for keys equal to key length
					for ($i = 1; $i <= $instance['key-length']; $i ++){
					$keyString = 'key-' . $i;
					echo "<p>";
					  echo "<label for=\"" . $this->get_field_id($keyString) . "\"> Business Key {$i}";
						echo "<input class=\"widefat\"
												 id=\"" . $this->get_field_id($keyString) . "\" 
												 name=\"" . $this->get_field_name($keyString) . "\" 
												 type=\"text\" 
												 value=\"" . $instance[$keyString] . "\" /><i class=\"delete-key dashicons dashicons-no\"></i>";
					  echo "</label>";
					echo "</p>";
				} ?>
			</div>
			<button id="add-key">Add Key</button>
			<button id="delete-key">Delete Key</button>
		</div>

		<?php
			$promotions = new Promotions();
			if( $promotions->call() === True ){
				$results = $promotions->getData();
				$levels = $results['data'];
			}
		?>

		<div class="search-params">
			<h3>Fill remaining slots with businesses that match the following criteria</h3>
			<p class="half-width">
			  <label for="<?=$this->get_field_id('address'); ?>">Center around address
				<input class="widefat" id="<?=$this->get_field_id('address'); ?>" name="<?=$this->get_field_name('address'); ?>" type="text" value="<?=$instance['address'];?>" />
			  </label>
			</p>
			<p class="half-width">
			  <label for="<?=$this->get_field_id('content_score'); ?>">Minimum content completeness score (0-99)
				<input class="widefat" id="<?=$this->get_field_id('content_score'); ?>" name="<?=$this->get_field_name('content_score'); ?>" type="text" value="<?=$instance['content_score'];?>" />
			  </label>
			</p>
			<p>
				<label for="<?= $this->get_field_id('sort_by'); ?>"> Sort by
					<select name="<?= $this->get_field_name('sort_by'); ?>" id="<?= $this->get_field_id('sort_by'); ?>" class="widefat">
							<option value="random" 		id="random" 	<?php echo $instance['sort_by'] == 'random' 	? 'selected="selected"' : '' ?>> Random	 </option>
							<option value="distance" 	id="distance" <?php echo $instance['sort_by'] == 'distance' ? 'selected="selected"' : '' ?>> Distance </option>
							<option value="name" 			id="name" 		<?php echo $instance['sort_by'] == 'name' 		? 'selected="selected"' : '' ?>> Name		 </option>
							<option value="content" 	id="content" 	<?php echo $instance['sort_by'] == 'content' 	? 'selected="selected"' : '' ?>> Content	 </option>
							<option value="" 					id="none" 		<?php echo $instance['sort_by'] == '' 				? 'selected="selected"' : '' ?>> Default	 </option>
					</select>
				</label>
			</p>

			<p class="half-width">
				<label for="<?= $this->get_field_id('large_block'); ?>"> Promotional level for large blocks
					<select name="<?= $this->get_field_name('large_block'); ?>" id="<?= $this->get_field_id('large_block'); ?>" class="widefat">
						<?php $this->ShowLevels($levels, $instance['large_block']); ?>
					</select>
				</label>
			</p>
			<p class="half-width">
				<label for="<?= $this->get_field_id('small_block'); ?>"> Promotional level for small blocks
					<select name="<?= $this->get_field_name('small_block'); ?>" id="<?= $this->get_field_id('small_block'); ?>" class="widefat">
						<?php $this->ShowLevels($levels, $instance['small_block']); ?>
					</select>
				</label>
			</p>
			<p class="half-width">
			  <label for="<?=$this->get_field_id('num_enhanced'); ?>"> Number of Enhanced: 
				<input class="widefat" id="<?=$this->get_field_id('num_enhanced'); ?>" name="<?=$this->get_field_name('num_enhanced'); ?>" type="text" value="<?=$instance['num_enhanced'];?>" />
			  </label>
			</p>
			<p class="half-width">
			  <label for="<?=$this->get_field_id('num_basic'); ?>"> Number of Basic: 
				<input class="widefat" id="<?=$this->get_field_id('num_basic'); ?>" name="<?=$this->get_field_name('num_basic'); ?>" type="text" value="<?=$instance['num_basic'];?>" />
			  </label>
			</p>
			<p>
				<label>Include only from the following categories
				<?php
					
					$ls_cat_tree = blankslate_get_categories();
					$cat_html = blankslate_print_widget_cats( $ls_cat_tree[0]['children'], 'none', $instance, 0, $this );
					echo $cat_html;

				?>
				<a href="javascript:(void);" class="ls-expand-cats">Expand All</a> 
			</p>
		</div>

		<input class="widefat" id="<?=$this->get_field_id('key-length'); ?>" name="<?=$this->get_field_name('key-length'); ?>" type="hidden" value="<?= $instance['key-length'] ? $instance['key-length'] : 0 ;?>" />

		<script type="text/javascript">
			(function(jQuery){
				//Template for key input element
				var keyTemplate = function( i ){
					return '<p>' +
					  '<label for="<?=$this->get_field_id("key-' + i + '"); ?>"> Business Key ' + i + ':' +
						'<input class="widefat" id="<?=$this->get_field_id("key-' + i + '"); ?>" name="<?=$this->get_field_name("key-' + i + '"); ?>" type="text" value="<?=$instance["key-' + i + '"];?>" />' +
					  '</label><i class="delete-key dashicons dashicons-no"></i>' +
					'</p>';
				}
					
				jQuery('.expand').live('click', function(e){
					e.preventDefault();
					e.stopPropagation();
					jQuery(this).parent().next('ul').toggle();
				});

				//On add, make new feild
				//Increment keylength value
				jQuery('#add-key').on('click', function(){
					var keyLengthSelector = "<?=$this->get_field_id('key-length'); ?>";
					var keyLength = jQuery('#' + keyLengthSelector).val();
					jQuery('.keys').append(keyTemplate(+keyLength + 1));
					jQuery('#' + keyLengthSelector).val(+keyLength + 1);
				});

				//On click delete, remove last item in list
				//Decrement keylength value
				jQuery('.keys').on('click', '.delete-key', function(){
					var keyLengthSelector = "<?=$this->get_field_id('key-length'); ?>";
					var keyLength = jQuery('#' + keyLengthSelector).val();

					jQuery(this).closest('p').remove();
					jQuery('#' + keyLengthSelector).val(+keyLength - 1);

					// if ( keyLength > 0 ){
					// 	jQuery('.keys p:last').remove();
					// 	jQuery('#' + keyLengthSelector).val(+keyLength - 1);
					// }
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
		$instance['seeMoreText'] 		= esc_attr( strip_tags($new_instance['seeMoreText']) );
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

		$instance['key-length'] = esc_attr( strip_tags($new_instance['key-length']) );

		$keys = array();
		for ( $i = 0; $i < $instance['key-length']; $i ++){
			$instance['key-' . $i + 1] = esc_attr( strip_tags($new_instance['key-' . $i + 1]) );
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
						</h3>
					</header>
				<?php endif; ?>

				<?php
					$key_array = array();
					for ( $i = 1; $i <= $instance['key-length']; $i ++){
						array_push($key_array, $instance['key-' . $i]);
					}
					$key_string = implode(',', $key_array);

					$patterns = array( 'loop_one_three', 'loop_six', 'loop_three_one', 'loop_two',  'loop_large_small', 'loop_four');
					$businesses = array();

					//Return arrays of businesses on different promotion levels
					$query = array();
					$query['cat'] = $categories;
					$query['promote_on'] = $smallLevel;

					if ( count($key_array) > 0 ){
						$keyquery = array();
						$keyquery['keys'] = $key_string;
						$keyquery['sort'] = 'none';
						$featured = new SearchResults(null, $keyquery);

						if( $featured->call() === true ){
							$results = $featured->getData();
							$businesses = array_merge($businesses, $results['data']);
						}
					}
					
					$query['lat'] = $lat;
					$query['lng'] = $lng;
					$query['sort'] = $sortBy;
					if(!empty($numBasic)){
						$query['rp'] = $numBasic;
					}
					
					if(!empty($content_score) && $content_score > 0 ){
						$query['content_score'] = $content_score;
					}
					
					// If promotion selected, use Promotions API
					if ( !empty($query['promote_on']) ){
						$promoted = new Promoted(null, $query);
						if( $promoted->call() === True ){
							$results = $promoted->getData();
							$businesses = array_merge($businesses, $results['data']);
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
								loop_two($premium, array());
							}
							while(current($businesses)){
								loop_six(array(), $businesses);
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
					//Use Search API if no promotions are selected
					} else { 


						$featured = new SearchResults(null, $query);

						if( $featured->call() === true ){
							$results = $featured->getData();
							$businesses = array_merge($businesses, $results['data']);
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
				<?php if($seeMore && $seeMoreText ){?>
					<a href="<?php echo $seeMore;?>" class="widget-see-more--bottom">
						<span class="content"><?= $seeMoreText ?></span>
						<i class="icon-chevron-right"></i>
					</a>
				<?php } ?>
			</div>

		<?php echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateDirectoryPatternLoop");'));