<?php

namespace Drupal\crypto_copyright\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Crypto_copyright' block.
 *
 * @Block(
 *  id = "crypto_copyright",
 *  admin_label = @Translation("Crypto copyright"),
 *  category = @Translation("Crypto copyright")
 * )
 */
class CopyrightBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#markup' => '<div class="copyright">&copy; Copyright '.date("Y").' <a href="https://drupalchamp.org/">Douce Infotech Pvt. Ltd.</a></div>',
	  '#cache' => ['max-age' => 0],
    );
  }

}
