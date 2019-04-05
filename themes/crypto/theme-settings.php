<?php

/**
 * @file
 * Manage theme settings.
 */

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function crypto_form_system_theme_settings_alter(&$form, &$form_state) {

  $form['#attached']['library'][] = 'crypto/theme-settings';
  $form['#attached']['library'][] = 'crypto/spectrum';

  $form['crypto_settings'] = [
    '#type' => 'fieldset',
    '#title' => t('Crypto Theme Settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  ];

  $form['crypto_settings']['tabs'] = [
    '#type' => 'vertical_tabs',
    '#default_tab' => 'basic_tab',
  ];

  $form['crypto_settings']['basic_tab'] = [
    '#type' => 'details',
    '#title' => t('Basic Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  ];

  $form['crypto_settings']['basic_tab']['body'] = [
    '#type' => 'details',
    '#title' => t('Body'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['basic_tab']['body']['body_color'] = [
    '#type' => 'textfield',
    '#title' => t('color'),
    '#default_value' => theme_get_setting('body_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['basic_tab']['body']['body_font_size'] = [
    '#type' => 'textfield',
    '#title' => t('Font Size'),
    '#default_value' => theme_get_setting('body_font_size', 'crypto'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['basic_tab']['body']['body_line_height'] = [
    '#type' => 'textfield',
    '#title' => t('Line Height'),
    '#default_value' => theme_get_setting('body_line_height', 'crypto'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['basic_tab']['body']['body_background'] = [
    '#type' => 'textfield',
    '#title' => t('Backgound color'),
    '#default_value' => theme_get_setting('body_background', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['basic_tab']['header'] = [
    '#type' => 'details',
    '#title' => t('Header'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['basic_tab']['header']['header_width'] = [
    '#type' => 'textfield',
    '#title' => t('Width'),
    '#description'   => t('Please enter width in %'),
    '#default_value' => theme_get_setting('header_width', 'crypto'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['basic_tab']['header']['header_backgound'] = [
    '#type' => 'textfield',
    '#title' => t('Backgound color'),
    '#default_value' => theme_get_setting('header_backgound', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['basic_tab']['header']['header_text_color'] = [
    '#type' => 'textfield',
    '#title' => t('Text Color'),
    '#default_value' => theme_get_setting('header_text_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['basic_tab']['header']['header_link_text_color'] = [
    '#type' => 'textfield',
    '#title' => t('Link Color'),
    '#default_value' => theme_get_setting('header_link_text_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['basic_tab']['header']['header_link_hover_active_color'] = [
    '#type' => 'textfield',
    '#title' => t('Link Hover/Active Color'),
    '#default_value' => theme_get_setting('header_link_hover_active_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['basic_tab']['main_container'] = [
    '#type' => 'details',
    '#title' => t('Main Container'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['basic_tab']['main_container']['main_container_width'] = [
    '#type' => 'textfield',
    '#title' => t('Width'),
    '#description'   => t('Please enter width in %'),
    '#default_value' => theme_get_setting('main_container_width', 'crypto'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['basic_tab']['footer'] = [
    '#type' => 'details',
    '#title' => t('Footer'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['basic_tab']['footer']['footer_width'] = [
    '#type' => 'textfield',
    '#title' => t('Width'),
    '#description'   => t('Please enter width in %'),
    '#default_value' => theme_get_setting('footer_width', 'crypto'),
  ];

  $form['crypto_settings']['basic_tab']['footer']['footer_background'] = [
    '#type' => 'textfield',
    '#title' => t('Background color'),
    '#default_value' => theme_get_setting('footer_background', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['basic_tab']['footer']['footer_text_color'] = [
    '#type' => 'textfield',
    '#title' => t('Text Color'),
    '#default_value' => theme_get_setting('footer_text_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['basic_tab']['footer']['footer_link_text_color'] = [
    '#type' => 'textfield',
    '#title' => t('Link Color'),
    '#default_value' => theme_get_setting('footer_link_text_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['basic_tab']['footer']['footer_link_hover_active_color'] = [
    '#type' => 'textfield',
    '#title' => t('Link Hover/Active Color'),
    '#default_value' => theme_get_setting('footer_link_hover_active_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['link_property_tab'] = [
    '#type' => 'details',
    '#title' => t('Link Properties'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  ];

  $form['crypto_settings']['link_property_tab']['link_properties'] = [
    '#type' => 'details',
    '#title' => t('Link Properties'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['link_property_tab']['link_properties']['link_color'] = [
    '#type' => 'textfield',
    '#title' => t('Link Color'),
    '#default_value' => theme_get_setting('link_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['link_property_tab']['link_properties']['link_hover_active_color'] = [
    '#type' => 'textfield',
    '#title' => t('Hover/Active Color'),
    '#default_value' => theme_get_setting('link_hover_active_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['link_property_tab']['link_button'] = [
    '#type' => 'details',
    '#title' => t('Link Button'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['link_property_tab']['link_button']['morelink'] = [
    '#type' => 'item',
    '#markup' => '<div class="theme-settings-morelink">' . t("you can use class='morelink' for link button ") . '</div>',
  ];

  $form['crypto_settings']['link_property_tab']['link_button']['link_button_font_size'] = [
    '#type' => 'textfield',
    '#title' => t('Font Size'),
    '#default_value' => theme_get_setting('link_button_font_size', 'crypto'),
  ];

  $form['crypto_settings']['link_property_tab']['link_button']['link_button_line_height'] = [
    '#type' => 'textfield',
    '#title' => t('Line Height'),
    '#default_value' => theme_get_setting('link_button_line_height', 'crypto'),
  ];

  $form['crypto_settings']['link_property_tab']['link_button']['link_button_text_color'] = [
    '#type' => 'textfield',
    '#title' => t('Button Text Color'),
    '#default_value' => theme_get_setting('link_button_text_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['link_property_tab']['link_button']['link_button_background_color'] = [
    '#type' => 'textfield',
    '#title' => t('Button Background'),
    '#default_value' => theme_get_setting('link_button_background_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['link_property_tab']['link_button']['link_button_text_hover_active_color'] = [
    '#type' => 'textfield',
    '#title' => t('Button Hover/Active Text Color'),
    '#default_value' => theme_get_setting('link_button_text_hover_active_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['link_property_tab']['link_button']['link_button_background_hover_active_color'] = [
    '#type' => 'textfield',
    '#title' => t('Button Hover/Active Background'),
    '#default_value' => theme_get_setting('link_button_background_hover_active_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['link_property_tab']['button'] = [
    '#type' => 'details',
    '#title' => t('Button'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['link_property_tab']['button']['button_font_size'] = [
    '#type' => 'textfield',
    '#title' => t('Font Size'),
    '#default_value' => theme_get_setting('button_font_size', 'crypto'),
  ];

  $form['crypto_settings']['link_property_tab']['button']['button_line_height'] = [
    '#type' => 'textfield',
    '#title' => t('Line Height'),
    '#default_value' => theme_get_setting('button_line_height', 'crypto'),
  ];

  $form['crypto_settings']['link_property_tab']['button']['button_text_color'] = [
    '#type' => 'textfield',
    '#title' => t('Button Text Color'),
    '#default_value' => theme_get_setting('button_text_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['link_property_tab']['button']['button_background_color'] = [
    '#type' => 'textfield',
    '#title' => t('Button Background'),
    '#default_value' => theme_get_setting('button_background_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['link_property_tab']['button']['button_text_hover_active_color'] = [
    '#type' => 'textfield',
    '#title' => t('Button Hover/Active Text Color'),
    '#default_value' => theme_get_setting('button_text_hover_active_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['link_property_tab']['button']['button_background_hover_active_color'] = [
    '#type' => 'textfield',
    '#title' => t('Button Hover/Active Background'),
    '#default_value' => theme_get_setting('button_background_hover_active_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['heading_tab'] = [
    '#type' => 'details',
    '#title' => t('Headings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  ];

  $form['crypto_settings']['heading_tab']['page_heading'] = [
    '#type' => 'details',
    '#title' => t('Page Heading'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['heading_tab']['page_heading']['page_heading_color'] = [
    '#type' => 'textfield',
    '#title' => t('Color'),
    '#default_value' => theme_get_setting('page_heading_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['heading_tab']['page_heading']['page_heading_font_size'] = [
    '#type' => 'textfield',
    '#title' => t('Font Size'),
    '#default_value' => theme_get_setting('page_heading_font_size', 'crypto'),
  ];

  $form['crypto_settings']['heading_tab']['page_heading']['page_heading_line_height'] = [
    '#type' => 'textfield',
    '#title' => t('Line Height'),
    '#default_value' => theme_get_setting('page_heading_line_height', 'crypto'),
  ];

  $form['crypto_settings']['heading_tab']['page_heading']['page_heading_background_color'] = [
    '#type' => 'textfield',
    '#title' => t('Backgrond Color'),
    '#default_value' => theme_get_setting('page_heading_background_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['heading_tab']['heading'] = [
    '#type' => 'details',
    '#title' => t('Heading (H1,H2,H3,H4,H5,H6)'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['heading_tab']['heading']['heading_color'] = [
    '#type' => 'textfield',
    '#title' => t('Heading Color'),
    '#default_value' => theme_get_setting('heading_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['heading_tab']['heading']['h1_tag'] = [
    '#type' => 'details',
    '#title' => t('H1'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['heading_tab']['heading']['h1_tag']['h1_font_size'] = [
    '#type' => 'textfield',
    '#title' => t('Font Size'),
    '#default_value' => theme_get_setting('h1_font_size', 'crypto'),
  ];
  $form['crypto_settings']['heading_tab']['heading']['h1_tag']['h1_lineheight'] = [
    '#type' => 'textfield',
    '#title' => t('Line Height'),
    '#default_value' => theme_get_setting('h1_lineheight', 'crypto'),
  ];

  $form['crypto_settings']['heading_tab']['heading']['h2_tag'] = [
    '#type' => 'details',
    '#title' => t('H2'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['heading_tab']['heading']['h2_tag']['h2_font_size'] = [
    '#type' => 'textfield',
    '#title' => t('Font Size'),
    '#default_value' => theme_get_setting('h2_font_size', 'crypto'),
  ];

  $form['crypto_settings']['heading_tab']['heading']['h2_tag']['h2_lineheight'] = [
    '#type' => 'textfield',
    '#title' => t('Line Height'),
    '#default_value' => theme_get_setting('h2_lineheight', 'crypto'),
  ];

  $form['crypto_settings']['heading_tab']['heading']['h3_tag'] = [
    '#type' => 'details',
    '#title' => t('H2'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['heading_tab']['heading']['h3_tag']['h3_font_size'] = [
    '#type' => 'textfield',
    '#title' => t('Font Size'),
    '#default_value' => theme_get_setting('h3_font_size', 'crypto'),
  ];

  $form['crypto_settings']['heading_tab']['heading']['h3_tag']['h3_lineheight'] = [
    '#type' => 'textfield',
    '#title' => t('Line Height'),
    '#default_value' => theme_get_setting('h3_lineheight', 'crypto'),
  ];

  $form['crypto_settings']['heading_tab']['heading']['h4_tag'] = [
    '#type' => 'details',
    '#title' => t('H4'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['heading_tab']['heading']['h4_tag']['h4_font_size'] = [
    '#type' => 'textfield',
    '#title' => t('Font Size'),
    '#default_value' => theme_get_setting('h4_font_size', 'crypto'),
  ];

  $form['crypto_settings']['heading_tab']['heading']['h4_tag']['h4_lineheight'] = [
    '#type' => 'textfield',
    '#title' => t('Line Height'),
    '#default_value' => theme_get_setting('h4_lineheight', 'crypto'),
  ];

  $form['crypto_settings']['heading_tab']['heading']['h5_tag'] = [
    '#type' => 'details',
    '#title' => t('H5'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['heading_tab']['heading']['h5_tag']['h5_font_size'] = [
    '#type' => 'textfield',
    '#title' => t('Font Size'),
    '#default_value' => theme_get_setting('h5_font_size', 'crypto'),
  ];

  $form['crypto_settings']['heading_tab']['heading']['h5_tag']['h5_lineheight'] = [
    '#type' => 'textfield',
    '#title' => t('Line Height'),
    '#default_value' => theme_get_setting('h5_lineheight', 'crypto'),
  ];

  $form['crypto_settings']['heading_tab']['heading']['h6_tag'] = [
    '#type' => 'details',
    '#title' => t('H6'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['heading_tab']['heading']['h6_tag']['h6_font_size'] = [
    '#type' => 'textfield',
    '#title' => t('Font Size'),
    '#default_value' => theme_get_setting('h6_font_size', 'crypto'),
  ];

  $form['crypto_settings']['heading_tab']['heading']['h6_tag']['h6_lineheight'] = [
    '#type' => 'textfield',
    '#title' => t('Line Height'),
    '#default_value' => theme_get_setting('h6_lineheight', 'crypto'),
  ];

  $form['crypto_settings']['paragraph_tab'] = [
    '#type' => 'details',
    '#title' => t('P (Paragraph)'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  ];

  $form['crypto_settings']['paragraph_tab']['paragraph'] = [
    '#type' => 'details',
    '#title' => t('P (Paragraph)'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['crypto_settings']['paragraph_tab']['paragraph']['paragraph_color'] = [
    '#type' => 'textfield',
    '#title' => t('Color'),
    '#default_value' => theme_get_setting('paragraph_color', 'crypto'),
    '#attributes' => ['class' => ['spectrum-color-picker'], 'id' => ['full']],
  ];

  $form['crypto_settings']['paragraph_tab']['paragraph']['paragraph_font_size'] = [
    '#type' => 'textfield',
    '#title' => t('Font Size'),
    '#default_value' => theme_get_setting('paragraph_font_size', 'crypto'),
  ];

  $form['crypto_settings']['paragraph_tab']['paragraph']['paragraph_lineheight'] = [
    '#type' => 'textfield',
    '#title' => t('Line Height'),
    '#default_value' => theme_get_setting('paragraph_lineheight', 'crypto'),
  ];
}
