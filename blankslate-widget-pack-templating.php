<?php

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

function loop_four( $primary, $secondary ){
  echo '<div class="grid loop-four">';
  for ( $i = 0; $i < 4; $i ++ ){
    $business = array_shift( $secondary );
    $bizClass = '';
    if ( !$business ){
      $bizClass = ' empty-no-biz';
    }
    echo '<div class="col-1-4' . $bizClass . '">';
    if ( $business ){
      print_business($business, 400, 400);
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
  
  //UTM config
  global $post;
  $post_slug = $post->post_name;
  $utm_medium = $post_slug;

  $parts = parse_url($business['url']);
  parse_str($parts['query'], $query);
  $utm_source = $query['pid'];

  $utm_campaign = 'pages_widget';

  if ( $business['promote_on'] ){
    $utm_content = implode(',', $business['promote_on']);
  }

  if ($business['photo']){ 
    $photo = $business['photo'];
  }

  echo  '<a class="wrap" target="_blank" href="' . $business['url'] .
              '&utm_medium=' . $utm_medium .
              '&utm_source=' . $utm_source .
              '&utm_campaign=' . $utm_campaign .
              ($utm_content ? ('&utm_content=' . $utm_content) : '') . '" >';
  echo    '<div class="img-hold">';
    if (strpos($photo,'googleapis') !== false) { ?>
      <img src="<?= BLANKSLATE_DIRECTORY_URL ?>scripts/timthumb.php?w=<?= $width ?>&h=<?= $height ?>&zc=1&src=<?= BLANKSLATE_DIRECTORY_PLACEHOLDER_URL ?>" alt="<?=$business['business_name']?>" >
    <?php } else { ?>
      <img src="<?=BLANKSLATE_DIRECTORY_URL?>scripts/timthumb.php?w=<?= $width ?>&h=<?= $height ?>&zc=1&src=<?= $photo ?>" alt="<?=$business['business_name']?>" >
    <?php }
  echo    '</div>';
  echo    '<div class="info-hold">';
  echo      '<span class="name">' . $business['business_name'] . '</span>';
  if ( $business['categories'] ){
    echo      '<span class="category">' . end($business['categories']) . '</span>';
  }
  echo      '<p>' . $business['about'] . '</p>';
  echo    '</div>';
  echo '</a>';
}