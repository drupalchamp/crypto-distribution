<?php

namespace Drupal\crypto_global\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'currency_detail' block.
 *
 * @Block(
 *  id = "currency_detail",
 *  admin_label = @Translation("Currency Detail"),
 *  category = @Translation("Custom Currency Detail")
 * )
 */
class CurrencyDetailBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = [];
    $current_path = \Drupal::service('path.current')->getPath();
    $path_args = explode('/', $current_path);

    if (!empty($path_args[2]) && ($path_args[1] == 'node' || $path_args[1] == 'currencies')) {
      if (isset($path_args[3])) {
        unset($path_args[3]);
      }
      $path_alias = implode('/', $path_args);
      $path = \Drupal::service('path.alias_manager')->getPathByAlias($path_alias);

      if (preg_match('/node\/(\d+)/', $path, $matches)) {
        $nid = $matches[1];
      }
    }

    if (!empty($nid)) {
      $node = Node::load($nid);

      $entity_img_id = $node->get('field_currency_image')->first()->getValue()['target_id'];
      $image = \Drupal::entityTypeManager()->getStorage('file')->load($entity_img_id);
      $style = \Drupal::entityTypeManager()->getStorage('image_style')->load('resize_64x64');
      $url = $style->buildUrl($image->getFileUri());

      $t_ids = $node->get('field_tags')->getValue();
      $field_tags = [];
      foreach ($t_ids as $t_id) {
        $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($t_id['target_id']);
        $field_tags[] = $term->getName();
      }

      $output['title'] = $node->getTitle();
      $output['field_currency_image'] = $url;
      $output['field_symbol'] = $node->field_symbol->get(0)->value;
      $output['field_website_link'] = $node->get('field_website_link')->getValue();
	  $output['field_announcement_link'] = $node->get('field_announcement_link')->getValue();
      $output['field_currency_explorer'] = $node->get('field_currency_explorer')->getValue();
      $output['field_curreny_message_board'] = $node->get('field_curreny_message_board')->getValue();
	  $output['field_chat_link'] = $node->get('field_chat_link')->getValue();
      $output['field_currency_source_code'] = $node->get('field_currency_source_code')->getValue();
      $output['field_currency_technical_doc'] = $node->get('field_currency_technical_doc')->getValue();
      $output['field_tags'] = $field_tags;
	  $output['current_time'] = date("Y M d h:i:s");

      $currency_nid = $nid;
	  $output['currency_nid'] = $currency_nid;

      $results = \Drupal::entityQuery('node')
        ->condition('type', 'markets')
        ->condition('status', 1)
        ->condition('field_base_currency', $currency_nid)
        ->execute();

      if (!empty($results)) {
        $market_nid = current($results);
        $market_data = Node::load($market_nid);

        if ($market_data->hasField('field_open') && !$market_data->get('field_open')->isEmpty()) {
          $output['open'] = $market_data->get('field_open')->value;
        }

		if ($market_data->hasField('field_volume') && !$market_data->get('field_volume')->isEmpty()) {
          $output['volume'] = $market_data->get('field_volume')->value;
        }

        if ($market_data->hasField('field_last') && !$market_data->get('field_last')->isEmpty()) {
          $output['price'] = $market_data->get('field_last')->value;
        }

        if ($market_data->hasField('field_ask') && !$market_data->get('field_ask')->isEmpty()) {
          $output['ask'] = $market_data->get('field_ask')->value;
        }

        if ($market_data->hasField('field_bid') && !$market_data->get('field_bid')->isEmpty()) {
          $output['bid'] = $market_data->get('field_bid')->value;
        }

        if ($market_data->hasField('field_high') && !$market_data->get('field_high')->isEmpty()) {
          $output['high'] = $market_data->get('field_high')->value;
        }

        if ($market_data->hasField('field_low') && !$market_data->get('field_low')->isEmpty()) {
          $output['low'] = $market_data->get('field_low')->value;
        }

		if ($market_data->hasField('field_low') && !$market_data->get('field_low')->isEmpty()) {
          $output['low'] = $market_data->get('field_low')->value;
        }

		if ($market_data->hasField('field_market_cap') && !$market_data->get('field_market_cap')->isEmpty()) {
          $output['market_cap'] = $market_data->get('field_market_cap')->value;
        }

		if ($market_data->hasField('field_circulating_supply') && !$market_data->get('field_circulating_supply')->isEmpty()) {
          $output['circulating_supply'] = $market_data->get('field_circulating_supply')->value;
        }

		if ($market_data->hasField('field_base_symbol') && !$market_data->get('field_base_symbol')->isEmpty()) {
          $symbol = $market_data->get('field_base_symbol')->value;
        }
      }
	  $chart_color = theme_get_setting('header_link_text_color', 'crypto');
	  if ($chart_color) {
		 $output['badge_bg'] = $chart_color;
	  }

	  
	}

    return [
      '#theme' => 'currency_detail',
      '#data' => $output,
      '#cache' => ['max-age' => 0],
    ];
  }

}
