<?php

namespace Drupal\crypto_import\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ImportDataController.
 */
class ImportDataController extends ControllerBase {

  /**
   * Declare Content method here.
   */
  public function content() {
    import_currencies_data();
    return ['#markup' => t('Currency Data Updated Successfully.')];
  }

}
