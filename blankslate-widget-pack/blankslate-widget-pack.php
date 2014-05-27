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
	if (!is_admin()){
		wp_enqueue_script('widget-pack', BLANKSLATE_WIDGET_PACK_URL . 'assets/js/widget-pack.js', false, '1.1', 'all');
		wp_enqueue_style('widget-pack', BLANKSLATE_WIDGET_PACK_URL . 'assets/css/widget-pack.css', false, '1.0', 'all');
	}
}
add_action('wp_enqueue_scripts','widget_pack_plugin_scripts');

?>