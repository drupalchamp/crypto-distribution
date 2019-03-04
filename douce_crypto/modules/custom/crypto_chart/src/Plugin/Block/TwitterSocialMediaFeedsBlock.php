<?php

namespace Drupal\crypto_chart\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;

/**
 * Defines a Twitter Social Media Feeds Block type.
 *
 * @Block(
 *   id = "twitter_social_media_feeds_block",
 *   admin_label = @Translation("Twitter Social Media Feeds Block"),
 *   category = @Translation("Social Media Feeds Block"),
 * )
 */
class TwitterSocialMediaFeedsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $current_path = \Drupal::service('path.current')->getPath();
    $path_args = explode('/', $current_path);

    if (isset($path_args[3]) && !empty($path_args[3]) && !empty($path_args[2]) && $path_args[1] == 'currencies') {
      unset($path_args[3]);

      $path_alias = implode('/', $path_args);
      $path = \Drupal::service('path.alias_manager')->getPathByAlias($path_alias);

      if (preg_match('/node\/(\d+)/', $path, $matches)) {
        $currency_nid = $matches[1];
        $currency_data = Node::load($currency_nid);
        if (!empty($currency_data) && ($currency_data->getType() == 'currency')) {
          $currency_name = $currency_data->getTitle();
          if ($currency_data->hasField('field_twitter_link') && !$currency_data->get('field_twitter_link')->isEmpty()) {
            $url = $currency_data->get('field_twitter_link')->uri;
            $isValidURL = UrlHelper::isValid($url, $absolute = FALSE);
            if ($isValidURL) {
              $build = [
                '#type' => 'link',
                '#title' => t('Twitter Timeline'),
                '#url' => Url::fromUri($url),
                '#attributes' => [
                  'class' => 'twitter-timeline',
                ],
                '#attached' => [
                  'library' => ['crypto_chart/twitter_widgets'],
                ],
              ];

              $build = [
                '#theme' => 'default',
                '#cache' => ['max-age' => 0],
                '#data' => render($build),
              ];

              return $build;
            }
          }
        }
      }
    }

    $build = [
      '#theme' => 'default',
      '#cache' => ['max-age' => 0],
      '#data' => '',
    ];

    return $build;
  }

}
