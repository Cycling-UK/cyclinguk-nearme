/**
 * Javascript to load the map into the map DIV.
 *
 * Using Drupal.behaviors means this function is called on page load, AJAX updates, etc.
 *
 * The map data is added to drupalSettings by template_preprocess_cyclinguk_nearme_map().
 */

import {
    CyclingUKMap
} from 'https://cycling-uk-d9.cycle.travel/maps/cycling_uk/scripts/app_bundle.js';

// noinspection JSUnresolvedReference
Drupal.behaviors.cyclinguk_nearme_loadmap = {
    attach: function (context, settings) {
        var map = new CyclingUKMap('map-embedded', drupalSettings.cyclinguk_nearme.map_data);
    }
}
