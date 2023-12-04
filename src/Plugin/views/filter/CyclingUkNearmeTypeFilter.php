<?php

namespace Drupal\cyclinguk_nearme\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;

/**
 * Simple filter to handle filtering geographical results by type.
 *
 * Sub-classing InOperator as that already has everything needed to handle a
 * list of possible options. We just need to specify the callback for the
 * options list, and tweak the default operators list.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("cyclinguk_nearme_type")
 */
class CyclingUkNearmeTypeFilter extends InOperator {

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL): void {
    parent::init($view, $display, $options);
    $this->valueTitle = t('Allowed geographical item types');
    $this->definition['options callback'] = [$this, 'generateOptions'];
  }

  /**
   * Only allow the "in" operator for now.
   */
  public function operators(): array {
    $operators = parent::operators();
    unset($operators['not in']);
    return $operators;
  }

  /**
   * Helper function that generates the options.
   *
   * @return array
   *   List of geographical type options.
   */
  public function generateOptions(): array {
    // Array keys are used to compare with the table field values.
    return [
      'routes' => $this->t('Routes'),
      'pois' => $this->t('Points of interest'),
      'events' => $this->t('Events'),
      'groups' => $this->t('Groups'),
      'posts' => $this->t('Posts'),
      'areas' => $this->t('Areas'),
    ];
  }

}
