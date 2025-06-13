<?php

namespace Drupal\cyclinguk_nearme\Plugin\views\field;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Render\Markup;
use Drupal\node\Plugin\views\field\Node;
use Drupal\views\ResultRow;

/**
 * Field handler to provide simple renderer that allows linking to a node.
 *
 * Definition terms:
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("cyclinguk_nearme_node")
 * @noinspection AnnotationMissingUseInspection
 */
class CyclingUkNearmeNodeField extends Node {

  /**
   * We don't want to do anything with SQL here, we're using an external API.
   */
  public function query(): void {
  }

  /**
   * If we've found a node, let the RenderedEntity field render it.
   *
   * Needed because we might not match a node, and a NULL _entity causes errors. Also hacks the data to fake
   * an additional "nid" field... might not be needed if the same thing can be done in hook_views_data()
   * in cyclinguk_nearme.views.inc?
   */
  public function render(ResultRow $values): MarkupInterface|string {
    if ($values->_entity) {
      $this->additional_fields['nid'] = TRUE;
      $this->aliases['nid'] = 'nid';
      $value = $values->_entity->label();
      return $this->renderLink($this->sanitizeValue($value), $values);
    }
    $this->options['alter']['make_link'] = FALSE;
    return Markup::create('<i>Not found</i>');
  }

}
