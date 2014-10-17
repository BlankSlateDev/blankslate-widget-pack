<?php
class ShortcodeBusiness {
  public $id;
  public $name;
  public $display_address;
  public $city;
  public $state;
  public $zip;
  public $about;
  public $url;
  public $photo;
  public $content;
  public $categories;

  /**
   * [__construct description]
   * @param int $id
   * @param str $name
   * @param str $display_address
   * @param str $city
   * @param str $state
   * @param str $zip
   * @param str $about
   * @param str $url
   * @param str $photo
   * @param str $content
   * @param array $categories
   */
  public function __construct($id, $name, $display_address, $city, $state, $zip, $about, $url, $photo, $content = null, $categories, $utm_content, $utm_campaign, $utm_medium, $utm_source ){
    $this->id                 = $id;
    $this->name               = $name;
    $this->display_address    = $display_address;
    $this->city               = $city;
    $this->state              = $state;
    $this->zip                = $zip;
    $this->about              = $about;
    $this->photo              = $photo;
    $this->content            = $content;
    $this->categories         = $categories;
    $this->utm_content        = $utm_content;
    $this->utm_campaign       = $utm_campaign;
    $this->utm_medium         = $utm_medium;
    $this->utm_source         = $utm_source;

    $this->url = $url . 
                "&utm_medium="    . $this->utm_medium .
                "&utm_source="    . $this->utm_source .
                "&utm_campaign="  . $this->utm_campaign .
                ($this->utm_content ? ("&utm_content=" . $this->utm_content) : '');
  }

  /**
   * Creates anchor tag html from content or name of business
   * @return str
   */
  public function make_anchor(){
    ob_start(); ?>
    <a href="<?= $this->url ?>">
      <?php if ( is_null( $this->content ) || empty( $this->content ) ) : ?>
        <?= $this->name ?>
      <?php else: ?>
        <?= $this->content ?>
      <?php endif; ?>
    </a> <?php
    $html = ob_get_clean();
    return $html;
  }

  /**
   * Creates Sponsored Box html from name, address, about and photo.
   * @return str
   */
  public function make_sponsor(){ 
    ob_start(); ?>
    <ul>
      <li><?= $this->name ?></li>
      <li><?= $this->address ?></li>
      <li><?= $this->about ?></li>
      <li><img src="<?= $this->photo ?>" /></li>
    </ul> <?php 
    $html = ob_get_clean();
    return $html;
  }

  public function make_card( $show_photo ){
    ob_start();
    include( BLANKSLATE_WIDGET_PACK_DIR . '/templates/_card.php' );
    $html = ob_get_clean();
    return $html;
  }

  /**
   * Init function, takes type to determine which html function to run
   * @param  str $type
   * @return str
   */
  public function init( $type, $show_photo ){
    switch ( $type ) {
      case 'link':
        return $this->make_anchor();
        break;
      case 'sponsor':
        return $this->make_sponsor();
        break;
      case 'card':
        return $this->make_card( $show_photo );
        break;
      default:
        return 'failed';
        break;
    }
  }
}