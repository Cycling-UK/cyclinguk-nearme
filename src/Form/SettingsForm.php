<?php

namespace Drupal\cyclinguk_nearme\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Settings for Cycling UK Near-Me module.
 *
 * @noinspection PhpUnused
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'cyclinguk_nearme_admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('cyclinguk_nearme.settings');
    $form['get'] = [
      '#type' => 'details',
      '#title' => $this->t('Connection to get data'),
      '#open' => TRUE,
    ];
    $form['get']['api_get_url_live'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Live database URL for getting data for Views.'),
      '#default_value' => $config->get('cyclinguk_nearme.api_get_url_live'),
    ];
    $form['get']['api_get_url_test'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Test database URL for getting data for Views.'),
      '#default_value' => $config->get('cyclinguk_nearme.api_get_url_test'),
    ];
    $form['get']['api_get_mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Mode'),
      '#options' => [
        'live' => $this->t('Live'),
        'test' => $this->t('Test'),
      ],
      '#default_value' => $config->get('cyclinguk_nearme.api_get_mode'),
    ];
    $form['push'] = [
      '#type' => 'details',
      '#title' => $this->t('Connection to push changes'),
      '#open' => TRUE,
    ];
    $form['push']['api_push_url_live'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Live database URL for pushing updated data.'),
      '#default_value' => $config->get('cyclinguk_nearme.api_push_url_live'),
    ];
    $form['push']['api_push_url_test'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Test database URL for pushing updated data.'),
      '#default_value' => $config->get('cyclinguk_nearme.api_push_url_test'),
    ];
    $form['push']['api_push_mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Mode'),
      '#options' => [
        'live' => $this->t('Live (push updates to the Live database)'),
        'test' => $this->t('Test (push updates to the Test database)'),
        'off' => $this->t('Off (do not push any updates)'),
      ],
      '#default_value' => $config->get('cyclinguk_nearme.api_push_mode'),
    ];
    $form['api_password'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API password'),
      '#default_value' => $config->get('cyclinguk_nearme.api_password'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $config = $this->config('cyclinguk_nearme.settings');
    $config->set('cyclinguk_nearme.api_get_url_live', $form_state->getValue('api_get_url_live'));
    $config->set('cyclinguk_nearme.api_get_url_test', $form_state->getValue('api_get_url_test'));
    $config->set('cyclinguk_nearme.api_get_mode', $form_state->getValue('api_get_mode'));
    $config->set('cyclinguk_nearme.api_push_url_live', $form_state->getValue('api_push_url_live'));
    $config->set('cyclinguk_nearme.api_push_url_test', $form_state->getValue('api_push_url_test'));
    $config->set('cyclinguk_nearme.api_push_mode', $form_state->getValue('api_push_mode'));
    $config->set('cyclinguk_nearme.api_password', $form_state->getValue('api_password'));
    $config->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      'cyclinguk_nearme.settings',
    ];
  }

}
