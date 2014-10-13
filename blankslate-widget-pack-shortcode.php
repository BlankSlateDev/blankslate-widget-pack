<?php
/**
* BlankSlate Pages Shortcode
*/
function blankslate_pages_func( $atts, $content = null ){
  $utm_campaign = 'pages_card';

  global $post;
  $post_slug = get_post( $post )->post_name;
  $utm_medium   = $post_slug;

  $utm_source   = $publisher_key;

  extract( shortcode_atts( array(
          'id'          => 123,
          'type'        => 'link',
          'show_photo'  => true,
          'utm_content' => ''
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
    
    $parts = parse_url($url);
    parse_str($parts['query'], $query);
    $utm_source = $query['pid'];

    $myBusiness = new ShortcodeBusiness( $id, $business_name, $display_address, $city, $state, $zip, $about, $url, $photo, $content, $categories, $utm_content, $utm_campaign, $utm_medium, $utm_source );
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
    $plugin_array['blankslate_pages_shortcode_button'] = plugins_url( '/assets/js/text-button.js', __FILE__ );
    return $plugin_array;
}

function blankslate_pages_register_shortcode_button($buttons) {
   array_push($buttons, "blankslate_pages_shortcode_button");
   return $buttons;
}