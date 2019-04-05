<?php

namespace Drupal\crypto_chart\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class CryptoSocialController.
 */
class CryptoSocialController extends ControllerBase {

  /**
   * Declare title method here.
   */
  public function title() {
    $title = t('Social');

    $current_path = \Drupal::service('path.current')->getPath();
    $path_args = explode('/', $current_path);

    if (isset($path_args[3]) && $path_args[3] == 'social' && !empty($path_args[2]) && $path_args[1] == 'currencies') {
      unset($path_args[3]);

      $path_alias = implode('/', $path_args);
      $path = \Drupal::service('path.alias_manager')->getPathByAlias($path_alias);

      if (preg_match('/node\/(\d+)/', $path, $matches)) {
        $currency_nid = $matches[1];
        $currency_name = \Drupal::database()->query("SELECT title FROM {node_field_data} WHERE nid = :currency_nid", [':currency_nid' => $currency_nid])->fetchField();
        $title = t('@name Social', ['@name' => $currency_name]);
      }
    }

    return $title;
  }

  /**
   * Declare Content method here.
   */
  public function content() {
    $output = '';
    return [
      '#type' => 'markup',
      '#markup' => $output,
    ];
  }

}
