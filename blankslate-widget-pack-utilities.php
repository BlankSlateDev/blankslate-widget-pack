<?php
function shortenString($string, $numChars = 25){
	$sliced = false;
	$string = trim( $string );
	
	//Take only first numChars characters
	if ( strlen($string) > $numChars ){
		$string = substr($string, 0, $numChars);
		$sliced = true;
	}
	
	//If was shortened, add ellipses to end of string.
	if ($sliced){
		$string .= '...';
	}
	
	return $string;
}

function BlankSlate_Get_All_Wordpress_Menus(){
	return get_terms( 'nav_menu', array( 'hide_empty' => true ) ); 
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