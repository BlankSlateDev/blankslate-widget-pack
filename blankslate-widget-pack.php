<?php
/*
Plugin Name: BlankSlate Widget Pack
Plugin URI:  https://github.com/BlankSlateDev/blankslate-widget-pack
Description: Collection of Widgets to Enhance BlankSlate Directory and Publishing Tools.
Version: 2.2.3
Author: BlankSlate Factory, Inc.
Author URI: http://blankslate.com
Text Domain: blankslate
License: GPLv2 or later
Copyright 2014 by BlankSlate
Please contact BlankSlate for details
*/

// require 'plugin-updates/plugin-update-checker.php';
// $MyUpdateChecker = PucFactory::buildUpdateChecker(
// 	'http://pages-dev.blankslate.com/plugins/blankslate-widget-pack/info.json',
// 	__FILE__,
// 	'blankslate-widget-pack'
// );

define('BLANKSLATE_WIDGET_PACK_DIR', plugin_dir_path(__FILE__));
define('BLANKSLATE_WIDGET_PACK_URL', plugin_dir_url(__FILE__));

require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-business-loop-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-post-and-businesses-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-map-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-neighborhood-slider.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-neighborhood-slider-v2.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-directory-blogroll-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-hero-unit-widget.php' );

require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-pattern-loop-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-category-cards-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-showcase-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-tile-businesses-widget.php' );

require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-header-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-sidebar-businesses-widget.php' );

require( BLANKSLATE_WIDGET_PACK_DIR . 'blankslate-widget-pack-utilities.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'blankslate-widget-pack-templating.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'blankslate-widget-pack-shortcode.php' );

/**
 * Classes
 */
require( BLANKSLATE_WIDGET_PACK_DIR . 'classes/ShortcodeBusiness.php' );

function widget_pack_plugin_scripts(){
	wp_enqueue_script('widget-pack-script', BLANKSLATE_WIDGET_PACK_URL . 'assets/js/widget-pack.js', false, '1.81', 'all');
	wp_enqueue_script('widget-pack-masonry', BLANKSLATE_WIDGET_PACK_URL . 'lib/masonry/masonry.min.js', false, '3.1.5', 'all');
	wp_enqueue_script('widget-pack-imagesLoaded', BLANKSLATE_WIDGET_PACK_URL . 'lib/imagesLoaded/imagesLoaded.min.js', false, '3.1.8', 'all');
	wp_enqueue_style('widget-pack-style', BLANKSLATE_WIDGET_PACK_URL . 'assets/css/widget-pack.css', false, '2.0.16', 'all');
  wp_enqueue_style('widget-pack-icomoon', BLANKSLATE_WIDGET_PACK_URL . 'fonts/icomoon/icomoon.css', false, '1.0.6', 'all');
}
add_action('wp_enqueue_scripts','widget_pack_plugin_scripts');
add_action('admin_enqueue_scripts', 'widget_pack_plugin_scripts');