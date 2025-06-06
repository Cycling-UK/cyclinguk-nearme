<?php

namespace Drupal\cyclinguk_nearme\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\filter\FilterPluginBase;

/**
 * Simple filter to handle filtering geographical results by type.
 *
 * @ViewsFilter("cyclinguk_nearme_pointradius")
 */
class CyclingUkNearmePointRadiusFilter extends FilterPluginBase {

  public $no_operator = TRUE;

  /**
   * {@inheritdoc}
   *
   * "Configure filter criterion" form.
   */
  protected function valueForm(&$form, FormStateInterface $form_state): void {
    $form['value'] = ['#tree' => TRUE];
    $form['value']['lat'] = [
      '#type' => 'number',
      '#min' => -90,
      '#max' => 90,
      '#step' => 'any',
      '#title' => $this->t('Latitude'),
      '#field_suffix' => $this->t('degrees'),
      '#default_value' => $this->value['lat'] ?? '',
      '#attributes' => ['id' => 'latitude'],
      //'#required' => TRUE,
    ];
    $form['value']['lon'] = [
      '#title' => $this->t('Longitude'),
      '#type' => 'number',
      '#min' => -180,
      '#max' => 180,
      '#step' => 'any',
      '#field_suffix' => $this->t('degrees'),
      '#default_value' => $this->value['lon'] ?? '',
      '#attributes' => ['id' => 'longitude'],
      //'#required' => TRUE,
    ];
    $form['value']['miles'] = [
      '#title' => $this->t('Radius'),
      '#type' => 'number',
      '#min' => 0,
      '#max' => 90,
      '#step' => 'any',
      '#field_suffix' => $this->t('miles from '),
      '#default_value' => $this->value['miles'] ?? '20',
      '#required' => TRUE,
    ];
  }

  /**
   * Build the exposed form with autocomplete placename and hidden lat, lon.
   */
  public function buildExposedForm(&$form, FormStateInterface $form_state) {
    if (empty($this->options['exposed'])) {
      return;
    }
    parent::buildExposedForm($form, $form_state);
    $identifier = $this->options['expose']['identifier'];
    $wrapper = $identifier . '_wrapper';
    $form[$wrapper][$identifier]['lat']['#type'] = 'hidden';
    $form[$wrapper][$identifier]['lon']['#type'] = 'hidden';
    $form[$wrapper][$identifier]['autocomplete'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Placename'),
      '#prefix' => '<div id="autocomplete_container">',
      '#suffix' => '</div>',
      '#default_value' => '',
      '#attached' => [
        'library' => 'cycle_travel_mapping/autocomplete',
      ],
    ];
    $placename = $this->value['placename'] ?? '';
    $form[$wrapper][$identifier]['placename'] = [
      '#type' => 'hidden',
      '#attributes' => ['id' => 'placename'],
      '#default_value' => $placename,
    ];
  }

  /**
   *
   */
  public function submitExposed(&$form, FormStateInterface $form_state) {
    $values = [
      'lat' => $form_state->getValue('lat'),
      'lon' => $form_state->getValue('lon'),
      'miles' => $form_state->getValue('miles'),
    ];
    $this->query->addWhere($this->options['group'], 'pointradius', $values);
  }

  /**
   * Add a pointradius "where clause" to the query.
   */
  public function query() {
    $this->query->addWhere($this->options['group'], 'pointradius', $this->value);
  }

  /**
   * Display summary of configured values for view admin.
   */
  public function adminSummary() {
    if ($this->isAGroup()) {
      return $this->t('grouped');
    }
    if (!empty($this->options['exposed'])) {
      return $this->t('exposed');
    }
    return print_r($this->value, TRUE);
  }

}
