<?php

namespace Drupal\crypto_markets\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;

/**
 *
 */
class CryptoMarketsController extends ControllerBase {

  /**
   * Return the title of the markets page.
   */
  public function title() {
    $title = t('Markets');

    $current_path = \Drupal::service('path.current')->getPath();
    $path_args = explode('/', $current_path);

    if (isset($path_args[3]) && $path_args[3] == 'markets' && !empty($path_args[2]) && $path_args[1] == 'currencies') {
      unset($path_args[3]);

      $path_alias = implode('/', $path_args);
      $path = \Drupal::service('path.alias_manager')->getPathByAlias($path_alias);

      if (preg_match('/node\/(\d+)/', $path, $matches)) {
        $currency_nid = $matches[1];
        $currency_name = \Drupal::database()->query("SELECT title FROM {node_field_data} WHERE nid = :currency_nid", [':currency_nid' => $currency_nid])->fetchField();
        $title = t('@name Top USD Markets', ['@name' => $currency_name]);
      }
    }

    return $title;
  }

  /**
   * Return the markets lists of the currency.
   */
  public function content() {
    $output = ['#markup' => t('Currency Top USD Markets')];

    $current_path = \Drupal::service('path.current')->getPath();
    $path_args = explode('/', $current_path);

    if (isset($path_args[3]) && $path_args[3] == 'markets' && !empty($path_args[2]) && $path_args[1] == 'currencies') {
      unset($path_args[3]);

      $path_alias = implode('/', $path_args);
      $path = \Drupal::service('path.alias_manager')->getPathByAlias($path_alias);

      if (preg_match('/node\/(\d+)/', $path, $matches)) {
        $currency_nid = $matches[1];

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

        if (isset($symbol)) {
          $fsym = $symbol;
          $tsym = 'USD';
          $method = 'GET';
		  $key = \Drupal::config('crypto_api_key.settings')->get('api_key');
          $url = "https://min-api.cryptocompare.com/data/top/exchanges/full?fsym=$fsym&tsym=$tsym&limit=2000&api_key=$key";
          $options = ['timeout' => 600, 'headers' => ['Accept' => 'application/json']];

          try {
            $response = \Drupal::httpClient()->request($method, $url, $options);
            $code = $response->getStatusCode();
            if ($code == 200) {
              $body = $response->getBody()->getContents();
              $response_data = json_decode($body);
              // Print "<pre>"; print_r($response_data); print "<pre>"; die;.
              $count = 1;
              $rows = [];
              foreach ($response_data->Data->Exchanges as $data) {
                $exchange_url = str_replace(" ","-", strtolower($data->MARKET)); 
				$row = [
                  $count,
				  \Drupal::l($data->MARKET, Url::fromUserInput('/exchanges/'.$exchange_url, ['absolute' => TRUE, 'attributes' => ['class' => []]])),
                  $data->FROMSYMBOL . '/' . $data->TOSYMBOL,
                  '$' . $data->PRICE,
                  '$' . round($data->VOLUME24HOURTO, 2),
                  date('d/m/Y', $data->LASTUPDATE),
                ];

                $count++;
                $rows[] = $row;
              }

              $markets = [
                '#theme' => 'table',
                '#header' => ['#', 'Exchange', 'Market Name', 'Price', 'Volume(24h)', 'Last Updated'],
                '#rows' => $rows,
                '#attributes' => ['id' => 'markets-table'],
              ];

              $output = render($markets);
            }
          }
          catch (\Exception $e) {
            return FALSE;
          }

        }

      }
    }

    $build = [
      '#theme' => 'default',
      '#cache' => ['max-age' => 0],
      '#data' => $output,
    ];

    return $build;
  }

}
