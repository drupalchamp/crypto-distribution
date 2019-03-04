<?php

namespace Drupal\crypto_api_key\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure crypto api key settings for this site.
 */
class CryptoApiKeyForm extends ConfigFormBase {
  /**
 * @var string Config settings */
  const SETTINGS = 'crypto_api_key.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'crypto_api_key_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Crypto API KEY'),
      '#default_value' => @$config->get('api_key'),
	  '#description' => '<a href="https://cryptocompare.com">Generate an API key from here</a>',
	  '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('api_key', $form_state->getValue('api_key'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
