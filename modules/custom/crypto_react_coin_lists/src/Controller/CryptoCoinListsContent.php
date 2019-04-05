<?php
/**
 * @file
 * Contains \Drupal\crypto_react_coin_lists\Controller\CryptoCoinListsContent.
 */

namespace Drupal\crypto_react_coin_lists\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

/**
 * Class CryptoCoinListsContent.
 */
class CryptoCoinListsContent extends ControllerBase {

  /**
   * It will return html data.
   *
   * @return html
   *   Return html output.
   */
  public function content() {
    return [
      '#theme' => 'crypto_lists_content',
    ];
  }
}
