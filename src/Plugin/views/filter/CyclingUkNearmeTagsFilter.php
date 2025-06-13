<?php

namespace Drupal\cyclinguk_nearme\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\cyclinguk_nearme\Plugin\views\query\CyclingUkNearmeQuery;
use Drupal\views\Annotation\ViewsFilter;
use Drupal\views\Plugin\views\filter\FilterPluginBase;

/**
 * Simple filter to handle filtering geographical results by tags.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("cyclinguk_nearme_tags")
 */
class CyclingUkNearmeTagsFilter extends FilterPluginBase {

  public $no_operator = TRUE;

  /** @var $query CyclingUkNearmeQuery */
  public $query;

  /**
   * {@inheritdoc}
   *
   * "Configure filter criterion" form.
   */
  protected function valueForm(&$form, FormStateInterface $form_state): void {
    $form['value']['#tree'] = TRUE;
    $form['value']['tags'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tags'),
      '#default_value' => $this->value['tags'] ?? '',
      '#description' => t('Comma-separated list of tag strings.'),
    ];
  }

  public function query(): void {
    $value = $this->value['tags'] ?? '';
    $this->query->addTags($value);
  }

  /**
   * Display summary of configured values for view admin.
   */
  public function adminSummary(): string|TranslatableMarkup {
    if ($this->isAGroup()) {
      return $this->t('grouped');
    }
    if (!empty($this->options['exposed'])) {
      return $this->t('exposed');
    }
    $value = $this->value['tags'] ?? '';
    return '"' . $value . '"';
  }

}
