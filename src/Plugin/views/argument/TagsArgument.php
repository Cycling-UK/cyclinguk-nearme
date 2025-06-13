<?php

namespace Drupal\cyclinguk_nearme\Plugin\views\argument;

use Drupal\cyclinguk_nearme\Plugin\views\query\CyclingUkNearmeQuery;
use Drupal\views\Annotation\ViewsArgument;
use Drupal\views\Plugin\views\argument\StringArgument;

/**
 * Argument handler to accept a node type.
 *
 * @ViewsArgument("cyclinguk_nearme_tags_argument")
 */
class TagsArgument extends StringArgument {

  /** @var $query CyclingUkNearmeQuery */
  public $query;

  /**
   * Modify the "query" when this argument is used.
   *
   * No need for database tables, etc.
   *
   */
  public function query($group_by = FALSE): void {
    $this->query->addTags($this->value[0]);
  }

}
