/**
 * Javascript to load the map into the map DIV.
 *
 * Using Drupal.behaviors means this function is called on page load, AJAX updates, etc.
 *
 * The map data is added to drupalSettings by template_preprocess_cyclinguk_nearme_map().
 */

// noinspection JSUnresolvedReference
Drupal.behaviors.cyclinguk_nearme_loadmap = {
    attach: function (context, settings) {
        // noinspection JSUnresolvedReference
        initialiseMap('map-embedded', drupalSettings.cyclinguk_nearme.map_data);
    }
}
