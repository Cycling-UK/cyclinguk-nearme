<?php

namespace Drupal\cyclinguk_nearme\Plugin\views\argument;

use Drupal\views\Plugin\views\argument\StringArgument;

/**
 * Argument handler to accept a node type.
 *
 * @ViewsArgument("cyclinguk_nearme_route_name_argument")
 */
class RouteNameArgument extends StringArgument {

  /**
   * Modify the "query" when this argument is used.
   *
   * No need for database tables, etc.
   *
   */
  public function query($group_by = FALSE): void {
    $this->query->addWhere(NULL, 'route_name', $this->value[0]);
  }

}
