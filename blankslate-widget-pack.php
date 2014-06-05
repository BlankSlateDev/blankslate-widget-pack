<?php

/*
Plugin Name: BlankSlate Widget Pack
Description: Collection of Widgets to Enhance BlankSlate Directory and Publishing Tools.
Version: 0.0.1
Author: BlankSlate
Author URI: http://blankslate.com
Text Domain: blankslate
License: GPLv2 or later
*/

/*
Copyright 2014 by BlankSlate

Please contact BlankSlate for details
*/

define('BLANKSLATE_WIDGET_PACK_DIR', plugin_dir_path(__FILE__));
define('BLANKSLATE_WIDGET_PACK_URL', plugin_dir_url(__FILE__));

require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-business-loop-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-post-and-businesses-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-map-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-neighborhood-slider.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-directory-blogroll-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-hero-unit-widget.php' );

function widget_pack_plugin_scripts(){
		wp_enqueue_script('widget-pack', BLANKSLATE_WIDGET_PACK_URL . 'assets/js/widget-pack.js', false, '1.1', 'all');
		wp_enqueue_style('widget-pack', BLANKSLATE_WIDGET_PACK_URL . 'assets/css/widget-pack.css', false, '1.0', 'all');
}
add_action('wp_enqueue_scripts','widget_pack_plugin_scripts');

function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches[1][0];

  if(empty($first_img)) {
    $first_img = "http://placehold.it/100x100";
  }
  return $first_img;
}

?>