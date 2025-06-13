<?php

/**
 * @file
 * Standard Views API data table and field definitions.
 */

/**
 * Implements hook_views_data().
 *
 * Fake "table" descriptions for creating Views from geographical information.
 *
 * @noinspection PhpUnused
 */
function cyclinguk_nearme_views_data(): array {
  $data = [];
  $data['cyclinguk_nearme']['table']['group'] = t('Geographical data');
  $data['cyclinguk_nearme']['table']['base'] = [
    'title' => t('Geographical data'),
    'help' => t('Geographical data from https://cycling-uk.cycle.travel.'),
    'query_id' => 'cyclinguk_nearme',
  ];
  $data['cyclinguk_nearme']['UUID'] = [
    'title' => t('UUID'),
    'help' => t("Geographical entity's UUID."),
    'field' => [
      'id' => 'standard',
      'title' => t('UUID'),
      'help' => t("Geographical entity's UUID."),
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];
  $data['cyclinguk_nearme']['type'] = [
    'title' => t('Geographical item type'),
    'help' => t("E.g. Event, Route, Place, etc."),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'cyclinguk_nearme_type',
      'title' => t('Geographical item type'),
      'help' => t("E.g. Event, Route, Place, etc."),
    ],
  ];
  $data['cyclinguk_nearme']['tags'] = [
    'title' => t('Geographical item tags'),
    'help' => t("Tags applied to items."),
    'filter' => [
      'id' => 'cyclinguk_nearme_tags',
    ],
    'argument' => [
      'id' => 'cyclinguk_nearme_tags_argument',
    ],
  ];
  $data['cyclinguk_nearme']['pointradius'] = [
    'title' => t('Near point'),
    'help' => t('Specify geographical latitude and longitude for results to be within radius of, or map display centered on.'),
    'filter' => [
      'id' => 'cyclinguk_nearme_pointradius',
    ],
  ];
  $data['cyclinguk_nearme']['nid'] = [
    'title' => t('Node ID'),
    'help' => t('Node ID of the node with the same UUID as returned from the geographical database.'),
    'field' => [
      'id' => 'numeric',
    ],
  ];
  $data['cyclinguk_nearme']['node_title'] = [
    'title' => t('Node title'),
    'help' => t('Title of the node with the same UUID as returned from the geographical database, optionally linked to node.'),
    'field' => [
      'id' => 'cyclinguk_nearme_node',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];
  $data['cyclinguk_nearme']['node_rendered'] = [
    'title' => t('Node rendered'),
    'help' => t('The node with the same UUID as returned from the geographical database, rendered with a view mode (e.g. "Teaser").'),
    'field' => [
      'id' => 'cyclinguk_nearme_rendered_node',
    ],
  ];
  $data['cyclinguk_nearme']['area_id'] = [
    'title' => t('Area UUID'),
    'help' => t('Provide an Area UUID to use for a search.'),
    'argument' => [
      'id' => 'cyclinguk_nearme_area_id_argument',
    ],
  ];
  $data['cyclinguk_nearme']['area_name'] = [
    'title' => t('Area name'),
    'help' => t('Provide an Area name to use for a search or map display.'),
    'argument' => [
      'id' => 'cyclinguk_nearme_area_name_argument',
    ],
  ];
  $data['cyclinguk_nearme']['route_id'] = [
    'title' => t('Route UUID'),
    'help' => t('Provide a Route UUID to use for a search or map display.'),
    'argument' => [
      'id' => 'cyclinguk_nearme_route_id_argument',
    ],
  ];
  $data['cyclinguk_nearme']['route_name'] = [
    'title' => t('Route name'),
    'help' => t('Provide a Route name to use for a search.'),
    'argument' => [
      'id' => 'cyclinguk_nearme_route_name_argument',
    ],
  ];

  return $data;
}
