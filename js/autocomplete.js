/* jshint esversion: 6 */

import {
  AutocompleteSearch
} from 'https://cycling-uk-d9.cycle.travel/maps/cycling_uk/scripts/geocoder.js';

window.addEventListener("load", e => {
  var autocompleteSearch = new AutocompleteSearch("autocomplete_container", {
    content: document.getElementById('placename').value,
    focus: false,
    callback: item => {
      // Item clicked.
      document.getElementById('longitude').value = item.location[0];
      document.getElementById('latitude').value = item.location[1];
      document.getElementById('placename').value = item.name;
      document.getElementById('autocomplete-0-input').value = item.name;
      document.getElementById('autocomplete-0-input').setAttribute('name', 'placename');
      // ToDo: better way to find Form element to submit.
      document.getElementById('views-exposed-form-near-me-page-1').submit();
    },
    onActive: item => {
      // Item highlighted (by default, the first item).
      var autocomplete_id = 'autocomplete-' + item.__autocomplete_id + '-input';
      document.getElementById('longitude').value = item.location[0];
      document.getElementById('latitude').value = item.location[1];
      document.getElementById('placename').value = item.name;
      document.getElementById(autocomplete_id).value = item.name;
      document.getElementById('autocomplete-0-input').setAttribute('name', 'placename');
    }
  });
});

/*
@ToDo: perhaps use "item.__autocomplete_id" to select the autocomplete input element?
*/
