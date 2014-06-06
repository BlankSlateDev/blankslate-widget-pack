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

function blankslate_print_widget_cats ($arr, $parent, $selected, $pass = 0, $widget) {
  global $blankslate_prefix;

  $html = '<ul class="cat_list parent-'. $parent . ' level-'.$pass.'">' . PHP_EOL;
  foreach ( $arr as $v ) {
    if (!is_array($v['name'])){
      $html.= '<li class="level-' . $pass . '">';
      if ( array_key_exists('children', $v) ) {
        $html .= '<span class="expand" key="' . $v['key'] . '">+</span>';
      } else {
        $html .= '<span class="end" key="' . $v['key'] . '">o</span>';
      }

      $checked = '';
      if (!isset( $selected[$v['name']] )){
        $selected[$v['name']] = '';
      }

      $instance = $selected[$v['name']];

      if ( isset($instance) && $instance == 'on' ){
        $checked = ' checked="checked"';
      }

      $html .= '<input type="checkbox" ' .
              $checked .
             ' id="' . $widget->get_field_id( $v['name'] ) . '"' .
             ' name="' . $widget->get_field_name( $v['name'] ) . '"' .
             '>' . $v['name'] . '</li>' . PHP_EOL;
    }

    if ( array_key_exists('children', $v) ) {
      $html .= blankslate_print_widget_cats($v['children'], $v['key'], $selected, $pass+1, $widget);
    }
  }

  $html.= '</ul>' . PHP_EOL;
  return $html;
}

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