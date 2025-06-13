<?php

namespace Drupal\cyclinguk_nearme\Plugin\views\field;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Render\Markup;
use Drupal\views\Annotation\ViewsField;
use Drupal\views\Plugin\views\field\RenderedEntity;
use Drupal\views\ResultRow;

/**
 * Field handler to provide simple renderer that allows linking to a node.
 * Definition terms:
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("cyclinguk_nearme_rendered_node")
 */
class CyclingUkNearmeRenderedNodeField extends RenderedEntity {

  /**
   * We don't want to do anything with SQL here, we're using an external API.
   */
  public function query(): void {
  }

  /**
   * If we've found a node, let the RenderedEntity field render it.
   *
   * Needed because we might not match a node, and a NULL _entity causes errors.
   */
  public function render(ResultRow $values): MarkupInterface {
    if ($values->_entity) {
      return parent::render($values);
    }
    return Markup::create('<i>Node not found</i>');
  }

  /**
   * We're only rendering nodes here.
   */
  public function getEntityType(): string {
    return 'node';
  }

}
