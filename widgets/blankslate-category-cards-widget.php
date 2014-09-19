<?php
/*
Plugin Name: BlankSlate Directory Category Cards
Description: List of category pages of directory
Author: Blankslate (DH)
Version: 1.0.0
*/

class BlankSlateDirectoryCategoryCards extends WP_Widget {
	function BlankSlateDirectoryCategoryCards() {
		$widget_ops = array(
			'classname' => 'BlankSlateDirectoryCategoryCards', 
			'description' => 'List of category pages of directory' );

		$this->WP_Widget('BlankSlateDirectoryCategoryCards', 'BlankSlate Directory Category Cards', $widget_ops);
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
		  <label for="<?=$this->get_field_id('tagline'); ?>">Tagline:
			<input class="widefat" id="<?=$this->get_field_id('tagline'); ?>" name="<?=$this->get_field_name('tagline'); ?>" type="text" value="<?=$instance['tagline'];?>" />
		  </label>
		</p>
		<p>
		  <label for="<?=$this->get_field_id('num_tiles'); ?>">Number of Tiles:
			<input class="widefat" id="<?=$this->get_field_id('num_tiles'); ?>" name="<?=$this->get_field_name('num_tiles'); ?>" type="text" value="<?=$instance['num_tiles'];?>" />
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
		$instance['tagline'] = esc_attr( $new_instance['tagline'] );
		$instance['num_tiles'] = esc_attr( $new_instance['num_tiles'] );
		return $instance;
	}
	
/*
*		The Widget
**/
	function widget($args, $instance) {
	
		extract( $args );
		
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget; ?>

		<?php 
			$get_menus = BlankSlate_Get_All_Wordpress_Menus();
			$menu_id = '';
			foreach ($get_menus as $menu) {
				if ( $menu->name == 'Directory Categories'){
					$menu_id = $menu->term_id;
				}
			}
			$menus = wp_get_nav_menu_items( $menu_id ); 
			$numTiles = 15;
			if(!empty($instance['num_tiles'])){
				$numTiles = $instance['num_tiles'];
			}
			
			$tagline = 'Find local experts and businesses to help you with your next project or renovation.';
			if(!empty($instance['tagline'])){
				$tagline = $instance['tagline'];
			}
		?>

		<div class="bs-widget-pack category-cards">
			<div class="grid header">
        	<p><?=$tagline;?></p>
				<a class="promote-button" href="/<?=BLANKSLATE_BASEURL.'/join/'?>">Promote Your Business<i class="icon-chevron-right"></i></a>
			</div>

			<div class="desktop-menu">
			<div class="grid">
			<?php for ($i = 0; $i < $numTiles; $i ++){ ?>
				<?php $item = $menus[$i]; ?>
				<a class="card" href="<?= $item->url ?>">
					<span><?= $item->post_title ?></span>
					<div class="corner-triangle"></div>
				</a>
			<?php } ?>
				<a class="card showmore" href="#">
					<span>Show More</span>
					<div class="triangle"></div>
				</a>
			</div>
			<div class="more-categories hidden">
				<ul>
				<?php for ($i = $numTiles; $i < count($menus); $i ++) { ?>
					<?php $item = $menus[$i]; ?>
					<li>
						<a href="<?= $item->url ?>"><?= $item->post_title ?></a>
					</li>
				<?php } ?>
				</ul>
			</div>
			</div>

			<div class="mobile-categories-menu">
				<span class="mobile-categories-trigger">Browse Categories <i class="icon-caret-down"></i></span>
				<ul style="display:none;">
					<?php foreach ($menus as $menu ) { ?>
						<li><a href="<?= $menu->url ?>"><?= $menu->post_title ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>

		<?php echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("BlankSlateDirectoryCategoryCards");'));