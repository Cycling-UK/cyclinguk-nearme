## Introduction
#### TESTS
This module enables Drupal Views to work with the geographical database API at https://cycling-uk.cycle.travel/

Views can be built to list events, routes, groups, posts, areas, or to display a map showing a route, area, or centred
on a specified point location.

An exposed location filter enables auto-complete geocoding of a place name, to return a map and/or results lists
based on a search radius around that place name. "Near me" functionality.

## Requirements

* Drupal 9
* PHP 7.4 or later
* Views module
* https://cycling-uk.cycle.travel geographical API

## Configuration

Construct a new View based on **Geographical data**, add fields and filters.

Tip: create a View with a Cycling UK Nearme map format, and add tables/lists/cards of other geographical content
underneath by adding View Attachments with required display formats.

### Fields

* **Node title**: optionally as a link to the node.
* **Rendered node**: the node rendered in a specified view mode, such as "Teaser".

### Filters

* **Near point**: Specify a latitude, longitude and radius for the search. If exposed, this filter swaps the latitude
  and longitude inputs for a placename input which uses AJAX autocompletion and geocoding to set the location.
* **Geographical item type**: Show just routes, places, events, groups, areas, or any combination. Can be exposed.

### Contextual filters

* **Area UUID**: Specify an area UUID to return items within that area (e.g. "6c28b0e1-5db4-4b52-8839-b7371ed6c7b6").
* **Area name**: Specify an area name to return items within that area (e.g. "Kent").
* **Route UUID**: Specify a route UUID to return items near that route (e.g. "6c28b0e1-5db4-4b52-8839-b7371ed6c7b6").
* **Route name**: Specify a route name to return items near that route (e.g. "King Alfred's Way").

### Formats

* **Cycling UK Nearme map**: interactive map, centered on a point, or showing a route (by UUID), or an area (by name).

The map has many configuration settings for sidebar and filter panel, Ideas button, Search button, etc. To limit the
types of item to display, use a **Geographical item type** filter in the View.

**Note**: the map uses a fixed "zoom" level and does not (yet) make use of the "radius" filter.

## Maintainers

This module was written for Cycling UK by [Anthony Cartmell](mailto:ajcartmell@fonant.com)
