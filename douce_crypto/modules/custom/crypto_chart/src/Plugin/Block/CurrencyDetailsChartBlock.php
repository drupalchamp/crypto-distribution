<?php

namespace Drupal\crypto_chart\Plugin\Block;

use Drupal\node\NodeInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Defines a Currency Details Chart Block type.
 *
 * @Block(
 *   id = "currency_details_chart_block",
 *   admin_label = @Translation("Currency Details Chart Block"),
 *   category = @Translation("Currency Details Chart Block"),
 * )
 */
class CurrencyDetailsChartBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $output = $series_data = [];
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof NodeInterface) {
      if ($node->getType() == 'currency') {

        $currency_nid = $node->id(); /* Currency Node ID. */

        $results = \Drupal::entityQuery('node')
          ->condition('type', 'markets')
          ->condition('status', 1)
          ->condition('field_base_currency', $currency_nid)
          ->execute();

        if (!empty($results)) {
          $market_nid = current($results);
          $market_data = Node::load($market_nid);

          if ($market_data->hasField('field_base_symbol') && !$market_data->get('field_base_symbol')->isEmpty()) {
            $symbol = $market_data->get('field_base_symbol')->value;
          }
        }
        else {
          // IF no any markets added then automatically fetch the USD records from the default exchange.
          if ($node->hasField('field_symbol') && !$node->get('field_symbol')->isEmpty()) {
            $symbol = $node->get('field_symbol')->value;
          }
        }

        $output['symbol'] = @$symbol;
      }
    }

    $build = [
      '#theme' => 'currency_detail_chart',
      '#cache' => ['max-age' => 0],
      '#data' => $output,
    ];

    return $build;
  }

}
