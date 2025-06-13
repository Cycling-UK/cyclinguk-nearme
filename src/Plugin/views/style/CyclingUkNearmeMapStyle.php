<?php

namespace Drupal\cyclinguk_nearme\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Style plugin to render a map.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "cyclinguk_nearme_map",
 *   title = @Translation("Cycling UK Nearme map"),
 *   help = @Translation("Views style plugin to display a map"),
 *   theme = "cyclinguk_nearme_map",
 *   display_types = { "normal" }
 * )
 */
class CyclingUkNearmeMapStyle extends StylePluginBase {

  /**
   * Options for embedded map.
   *
   * Options for item types to show are set separately by the CyclingUKNearmeTypeFilter,
   * to be more consistent with database Views.
   *
   * {@inheritdoc}
   */
  protected function defineOptions(): array {
    $options = parent::defineOptions();
    $options['map_height'] = ['default' => '500px'];
    $options['zoom'] = ['default' => 8];
    $options['preset'] = ['default' => 'none'];
    $options['load_pois'] = ['default' => TRUE];
    $options['show_all_routes'] = ['default' => TRUE];
    $options['scrollproof'] = ['default' => TRUE];
    $options['side_panel_details'] = [
      'contains' => [
        'side_panel' => ['default' => 'closed'],
        'show_pois' => ['default' => TRUE],
        'show_routes' => ['default' => TRUE],
        'expand_solo_list' => ['default' => FALSE],
        'hide_if_empty' => ['default' => TRUE],
        'prefer_routes' => ['default' => TRUE],
        'prefer_flagship' => ['default' => TRUE],
        'pan_to_results' => ['default' => TRUE],
      ],
    ];
    $options['map_controls'] = [
      'contains' => [
        'show_ideas' => ['default' => FALSE],
        'show_search' => ['default' => FALSE],
        'show_fullscreen' => ['default' => TRUE],
        'search_routes' => ['default' => TRUE],
        'search_pois' => ['default' => TRUE],
      ],
    ];
    $options['filter_panel_details'] = [
      'contains' => [
        'filter_panel' => ['default' => 'closed'],
        'filter_pois' => ['default' => TRUE],
        'filter_routes' => ['default' => TRUE],
        'filter_groups' => ['default' => FALSE],
        'filter_events' => ['default' => FALSE],
      ],
    ];
    $options['elevation'] = ['default' => FALSE];
    $options['route_planner'] = ['default' => FALSE];
    $options['enable_sorting'] = ['default' => FALSE];
    $options['map_callback'] = ['default' => ''];
    return $options;
  }

  /**
   * Map display options.
   *
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state): void {
    parent::buildOptionsForm($form, $form_state);
    $form['map_height'] = [
      '#title' => $this->t('Map height'),
      '#type' => 'textfield',
      '#default_value' => $this->options['map_height'],
    ];
    $form['zoom'] = [
      '#title' => $this->t('Zoom level'),
      '#type' => 'number',
      '#default_value' => $this->options['zoom'],
    ];
    $form['preset'] = [
      '#title' => $this->t('Preset'),
      '#description' => $this->t('Load a set of pre-defined map settings.'),
      '#type' => 'select',
      '#options' => [
        'none' => '(none)',
        'county' => 'County',
        'area' => 'Area',
        'route' => 'Route',
        'poi' => 'Points of interest',
        'poi_single' => 'Point of interest',
        'location' => 'Location',
        'hub' => 'Hub',
        'multiple' => 'Multiple',
        'specified' => 'Specified',
        'route_planner' => 'Route Planner',
        'campaigns' => 'Campaigns',
      ],
      '#default_value' => $this->options['preset'],
    ];
    $form['load_pois'] = [
      '#type' => 'checkbox',
      '#title' => t('Load Points Of Interest when panning'),
      '#default_value' => $this->options['load_pois'],
    ];
    $form['show_all_routes'] = [
      '#type' => 'checkbox',
      '#title' => t('Show all Routes, not justExperience/flagship Routes'),
      '#default_value' => $this->options['show_all_routes'],
    ];
    $form['scrollproof'] = [
      '#type' => 'checkbox',
      '#title' => t('Scrollproof (require Ctrl+scroll to zoom the map)'),
      '#default_value' => $this->options['scrollproof'],
    ];
    // Radius.
    // Tags.
    // Side panel options.
    $form['side_panel_details'] = [
      '#type' => 'details',
      '#title' => $this->t('Side Panel'),
      '#open' => TRUE,
    ];
    $form['side_panel_details']['side_panel'] = [
      '#type' => 'radios',
      '#title' => $this->t('Show side panel'),
      '#options' => [
        'yes' => 'Yes, open by default',
        'closed_mobile' => 'Yes, open but closed by default on mobile devices',
        'closed' => 'Yes, but closed by default',
        'no' => 'No',
      ],
      '#default_value' => $this->options['side_panel_details']['side_panel'],
    ];
    $form['side_panel_details']['show_pois'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Places, Events and Groups'),
      '#states' =>
        ['invisible' => [':input[name="style_options[side_panel_details][side_panel]"]' => ['value' => 'no']]],
      '#default_value' => $this->options['side_panel_details']['show_pois'],
    ];
    $form['side_panel_details']['show_routes'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Routes'),
      '#states' =>
        ['invisible' => [':input[name="style_options[side_panel_details][side_panel]"]' => ['value' => 'no']]],
      '#default_value' => $this->options['side_panel_details']['show_routes'],
    ];
    $form['side_panel_details']['expand_solo_list'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Expand list of just one item to full view'),
      '#states' =>
        ['invisible' => [':input[name="style_options[side_panel_details][side_panel]"]' => ['value' => 'no']]],
      '#default_value' => $this->options['side_panel_details']['expand_solo_list'],
    ];
    $form['side_panel_details']['hide_if_empty'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide panel if nothing to show'),
      '#states' =>
        ['invisible' => [':input[name="style_options[side_panel_details][side_panel]"]' => ['value' => 'no']]],
      '#default_value' => $this->options['side_panel_details']['hide_if_empty'],
    ];
    $form['side_panel_details']['prefer_routes'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Prefer Routes over Places'),
      '#states' =>
        ['invisible' => [':input[name="style_options[side_panel_details][side_panel]"]' => ['value' => 'no']]],
      '#default_value' => $this->options['side_panel_details']['prefer_routes'],
    ];
    $form['side_panel_details']['prefer_flagship'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Prefer flagship/Experience content'),
      '#states' =>
        ['invisible' => [':input[name="style_options[side_panel_details][side_panel]"]' => ['value' => 'no']]],
      '#default_value' => $this->options['side_panel_details']['prefer_flagship'],
    ];
    $form['side_panel_details']['pan_to_results'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Pan to show results'),
      '#states' =>
        ['invisible' => [':input[name="style_options[side_panel_details][side_panel]"]' => ['value' => 'no']]],
      '#default_value' => $this->options['side_panel_details']['pan_to_results'],
    ];
    // Map controls options.
    $form['map_controls'] = [
      '#type' => 'details',
      '#title' => $this->t('Map Controls'),
      '#open' => TRUE,
    ];
    $form['map_controls']['show_fullscreen'] = [
      '#title' => $this->t('Show Full Screen button'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['map_controls']['show_fullscreen'],
    ];
    $form['map_controls']['show_ideas'] = [
      '#title' => $this->t('Show Ideas button'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['map_controls']['show_ideas'],
    ];
    $form['map_controls']['show_search'] = [
      '#title' => $this->t('Show Search button'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['map_controls']['show_search'],
    ];
    $form['map_controls']['search_routes'] = [
      '#title' => $this->t('Search routes'),
      '#type' => 'checkbox',
      '#states' =>
        ['visible' => [':input[name="style_options[map_controls][show_search]"]' => ['checked' => TRUE]]],
      '#default_value' => $this->options['map_controls']['search_routes'],
    ];
    $form['map_controls']['search_pois'] = [
      '#title' => $this->t('Search points of interest'),
      '#type' => 'checkbox',
      '#states' =>
        ['visible' => [':input[name="style_options[map_controls][show_search]"]' => ['checked' => TRUE]]],
      '#default_value' => $this->options['map_controls']['search_pois'],
    ];
    // Filter panel options.
    $form['filter_panel_details'] = [
      '#type' => 'details',
      '#title' => $this->t('Filter Panel'),
      '#open' => TRUE,
    ];
    $form['filter_panel_details']['filter_panel'] = [
      '#type' => 'radios',
      '#title' => $this->t('Show filter panel'),
      '#options' => [
        'yes' => 'Yes, open by default',
        'closed_mobile' => 'Yes, open but closed by default on mobile devices',
        'closed' => 'Yes, but closed by default',
        'no' => 'No',
      ],
      '#default_value' => $this->options['filter_panel_details']['filter_panel'],
    ];
    $form['filter_panel_details']['filter_pois'] = [
      '#title' => $this->t('Show Places filters'),
      '#type' => 'checkbox',
      '#states' =>
        ['invisible' => [':input[name="style_options[filter_panel_details][filter_panel]"]' => ['value' => 'no']]],
      '#default_value' => $this->options['filter_panel_details']['filter_pois'],
    ];
    $form['filter_panel_details']['filter_routes'] = [
      '#title' => $this->t('Show Route filters'),
      '#type' => 'checkbox',
      '#states' =>
        ['invisible' => [':input[name="style_options[filter_panel_details][filter_panel]"]' => ['value' => 'no']]],
      '#default_value' => $this->options['filter_panel_details']['filter_routes'],
    ];
    $form['filter_panel_details']['filter_groups'] = [
      '#title' => $this->t('Show Group filters'),
      '#type' => 'checkbox',
      '#states' =>
        ['invisible' => [':input[name="style_options[filter_panel_details][filter_panel]"]' => ['value' => 'no']]],
      '#default_value' => $this->options['filter_panel_details']['filter_groups'],
    ];
    $form['filter_panel_details']['filter_events'] = [
      '#title' => $this->t('Show Event filters'),
      '#type' => 'checkbox',
      '#states' =>
        ['invisible' => [':input[name="style_options[filter_panel_details][filter_panel]"]' => ['value' => 'no']]],
      '#default_value' => $this->options['filter_panel_details']['filter_events'],
    ];
    // @todo add these options so they can be turned off:
    // Presets:
    // county, area, route, location, hub, multiple, specified, routeplanner.
    $form['elevation'] = [
      '#title' => $this->t('Show elevation profile (requires RouteID or Route Planner'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['elevation'],
    ];
    $form['route_planner'] = [
      '#title' => $this->t('Show Route Planner'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['route_planner'],
    ];
    $form['enable_sorting'] = [
      '#title' => $this->t('Enable sorting'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['enable_sorting'],
    ];
    $form['callback'] = [
      '#title' => $this->t('Map callback'),
      '#description' => $this->t('JavaScript callback when actions are taken on the map; should be name of a JS function which takes a single Object (hash) argument'),
      '#type' => 'textfield',
      '#default_value' => $this->options['callback'],
    ];
  }

  /**
   * Validate map display options.
   *
   * @inheritDoc
   */
  public function validateOptionsForm(&$form, FormStateInterface $form_state): void {
    parent::validateOptionsForm($form, $form_state);
    // Trim callback function name.
    $field = ['style_options', 'callback'];
    $form_state->setValue($field, trim($form_state->getValue($field)));
  }

}
