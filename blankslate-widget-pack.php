<?php

/*
Plugin Name: BlankSlate Widget Pack
Description: Collection of Widgets to Enhance BlankSlate Directory and Publishing Tools.
Version: 1.0.4
Author: BlankSlate
Author URI: http://blankslate.com
Text Domain: blankslate
License: GPLv2 or later

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

require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-pattern-loop-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-category-cards-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-showcase-widget.php' );

require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-header-widget.php' );
require( BLANKSLATE_WIDGET_PACK_DIR . 'widgets/blankslate-sidebar-businesses-widget.php' );

require( BLANKSLATE_WIDGET_PACK_DIR . 'blankslate-widget-pack-utilities.php' );

/**
 * Classes
 */
require( BLANKSLATE_WIDGET_PACK_DIR . 'classes/ShortcodeBusiness.php' );

function widget_pack_plugin_scripts(){
	wp_enqueue_script('widget-pack', BLANKSLATE_WIDGET_PACK_URL . 'assets/js/widget-pack.js', false, '1.4', 'all');
	wp_enqueue_style('widget-pack', BLANKSLATE_WIDGET_PACK_URL . 'assets/css/widget-pack.css', false, '1.8.9', 'all');
  wp_enqueue_style('icomoon', BLANKSLATE_WIDGET_PACK_URL . 'fonts/icomoon/icomoon.css', false, '1.0.5', 'all');
}
add_action('wp_enqueue_scripts','widget_pack_plugin_scripts');
add_action('admin_enqueue_scripts', 'widget_pack_plugin_scripts');

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
    $first_img = BLANKSLATE_DIRECTORY_PLACEHOLDER_URL;
  }
  return $first_img;
}

function loop_two( $primary, $secondary ){
  echo '<div class="grid loop-two">';
  for ($i = 0; $i < 2; $i ++){
    $business = array_shift($primary);
    $bizClass = '';
    if(!$business){
    	$bizClass = ' empty-no-biz';
   	}
    echo '<div class="col-1-2' . $bizClass . '">';
    if ( $business ) {
      print_business($business, 300, 200);
    }
    echo '</div>';
  }
  echo '</div>';
}


function loop_six( $primary, $secondary ){
  echo '<div class="grid loop-six">';
  for ($i = 0; $i < 6; $i ++){
    $business = array_shift($secondary);
    $bizClass = '';
    if(!$business){
    	$bizClass = ' empty-no-biz';
   	}
    echo '<div class="col-1-6' . $bizClass . '">';
    if ( $business ) {
      print_business($business, 300, 200);
    }
    echo '</div>';
  }
  echo '</div>';
}

function loop_three_one( $primary, $secondary ){
  echo '<div class="grid loop-three-one">';
    //Do 3 Small Business
    for ($i = 0; $i < 3; $i ++){
      $bizClass = '';
      if(!$business){
    	$bizClass = ' empty-no-biz';
   	  }
      $business = array_shift($secondary);
      echo '<div class="col-1-6' . $bizClass . '">';
      if ( $business ) {
        print_business($business, 300, 200);
      }
      echo '</div>';
    }

    //Do Large Business
    $business = array_shift($primary);
     if(!$business){
    	$bizClass = ' empty-no-biz';
   	  }
    echo '<div class="col-1-2' . $bizClass . '">';
    if ( $business ) {
      print_business($business, 300, 200);
    }
    echo '</div>';
  echo '</div>';
}

function loop_one_three( $primary, $secondary ){
  echo '<div class="grid loop-one-three">';
    //Do Large Business
    $business = array_shift($primary);
    
    $bizClass = '';
    if(!$business){
    	$bizClass = ' empty-no-biz';
    }
    echo '<div class="col-1-2' . $bizClass . '">';
    if ( $business ) {
      print_business($business, 300, 200);
    }
    echo '</div>';

    //Do 3 Small Business
    for ($i = 0; $i < 3; $i ++){
      $business = array_shift($secondary);
      if(!$business){
      $bizClass = ' empty-no-biz';
      }
      echo '<div class="col-1-6' . $bizClass . '">';
      if ( $business ) {
        print_business($business, 300, 200);
      }
      echo '</div>';
    }
  echo '</div>';
}

function print_business($business, $width, $height){
  if ($business['photo']){ 
    $photo = $business['photo'];
  }

  echo  '<a class="wrap" target="_blank" href="' . $business['url'] . '" >';
  echo    '<div class="img-hold">';
    if (strpos($photo,'googleapis') !== false) { ?>
      <img src="<?= BLANKSLATE_DIRECTORY_URL ?>scripts/timthumb.php?w=<?= $width ?>&h=<?= $height ?>&zc=1&src=<?= BLANKSLATE_DIRECTORY_PLACEHOLDER_URL ?>" alt="<?=$business['business_name']?>" >
    <?php } else { ?>
      <img src="<?=BLANKSLATE_DIRECTORY_URL?>scripts/timthumb.php?w=<?= $width ?>&h=<?= $height ?>&zc=1&src=<?= $photo ?>" alt="<?=$business['business_name']?>" >
    <?php }
  echo    '</div>';
  echo    '<div class="info-hold">';
  echo      '<span class="name">' . $business['business_name'] . '</span>';
  echo      '<span class="category">' . end($business['categories']) . '</span>';
  echo      '<p>' . $business['about'] . '</p>';
  echo    '</div>';
  echo '</a>';
}

/**
* BlankSlate Pages Shortcode
*/
function blankslate_pages_func( $atts, $content = null ){
  extract( shortcode_atts( array(
          'id'    => 123,
          'type'  => 'link',
          'show_photo' => true,
          'tracking_id' => 'card'
  ), $atts, 'blankslate_pages' ) );

  $query = array();
  $query['keys'] = $id;

  $page = new SearchResults(null, $query);
  if( $page->call() === True ){
    $results = $page->getData();
    $businesses = $results['data'];
  }
  foreach ($businesses as $business) {
    extract($business); 
    $myBusiness = new ShortcodeBusiness( $id, $business_name, $display_address, $city, $state, $zip, $about, $url, $photo, $content, $categories, $tracking_id );
    $sCards .= $myBusiness->init( $type, $show_photo );
  }

  return $sCards;
}
add_shortcode( 'blankslate_pages', 'blankslate_pages_func' );

function blankslate_pages_shortcode_button() {
  global $typenow;
  if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    return;
  }
  if( !in_array( $typenow, array( 'post', 'page' ) ) )
    return;

  if ( get_user_option('rich_editing') == 'true') {
    add_filter("mce_external_plugins", "blankslate_pages_add_shortcode_button");
    add_filter('mce_buttons', 'blankslate_pages_register_shortcode_button');
  }
}
add_action('admin_head', 'blankslate_pages_shortcode_button');

function blankslate_pages_add_shortcode_button($plugin_array) {
    $plugin_array['blankslate_pages_shortcode_button'] = plugins_url( '/assets/js/text-button.js', __FILE__ ); // CHANGE THE BUTTON SCRIPT HERE
    return $plugin_array;
}

function blankslate_pages_register_shortcode_button($buttons) {
   array_push($buttons, "blankslate_pages_shortcode_button");
   return $buttons;
}