## JavaScript map configuration

Map configuration is done with a MapConfig class that takes a JSON object with options as defined below.

```javascript
class MapConfig {

    // Viewport and map configuration
    lat = null;
    lon = null;
    zoom = null;
    scrollproof = true;
    postcode = null;            // alternative to lat/lon
    poiMinZoom = null;            // minimum zoom level at which to show POIs (usually 7)
    auth = null;                // auth string for non-public content

    // Main content
    restrictTo        = null;    // restrict to a particular type of POI, without even loading any others
    routeID           = null;    // single route to show
    poiID             = null;    // single POI to show
    multiple          = null;    // comma-separated string listing multiple routes to show
    areaName          = null;    // area to show (either a string like "Norfolk", or an array like ["Norfolk","Suffolk"] )
    showRoutes        = true;    // show routes?
    showPOIs          = true;    // show points of interest?
    showGroups        = false;    // show groups?
    showEvents        = false;    // show events?
    showCampaigns     = false;    // show CAN groups/reps?
    loadPOIs          = true;    // load POIs when panning?
    allRoutes         = true;    // show all routes (true), or just a particular type? ('experience','flagship','adventure','challenge','long_distance' - can be comma-separated)
    radius            = 0;        // radius (in degrees) to preload POIs (requires lat,lon; doesn't apply if routeID or areaName)
    tags              = null;    // restrict map display to a particular tag

    // Sidepanel
    sidepanel         = true;    // sidepanel? (true, false, 'closed', 'closed_mobile')
    wideSidepanel     = true;    // increase sidepanel width?
    showPOISidepanel  = false;    // show POIs, events and groups in sidepanel?
    showRouteSidepanel= true;    // show routes in sidepanel?
    expandSoloList    = false;    // if there's only one item in a list, should we auto-expand it to full view?
    hideIfEmpty       = true;    // automatically hide if there's nothing to show?
    preferRoutes      = true;    // prefer routes over POIs?
    preferFlagship    = true;    // give an additional weighting to flagship/Experience/challenge &c. content?
    panToResults      = true;    // automatically pan map to show results?

    // Map controls
    showIdeas         = true;    // ideas button?
    showSearch        = true;    // search button?
    showFullScreen    = true;    // full-screen button?
    searchRoutes      = true;    // does search look for routes?
    searchPOIs        = true;    // does search look through POIs?

    // Filter panel
    filterPanel       = true;    // filter panel? (true, false, 'closed', 'closed_mobile')
    showPOIFilter     = true;    // show filter for POIs?
    showRouteFilter   = true;    // show filter for routes?
    showGroupFilter   = false;    // show filter for groups?
    showEventFilter   = false;    // show filter for events?
    showCampaignFilter= false;    // show filter for campaigns?
    showLayerFilter   = false;    // show filter for extra map layers?

    // Elevation
    elevation         = false;    // show elevation? (true, false, 'closed': requires routeID or routePlanner)

    // JS interaction
    callback          = null;    // callback when actions are taken on the map; should be a JS function which takes a single Object (hash) argument
    idBase            = "xxxxxxxx-xxxx-".replace(/x/g, c => (Math.random()*16|0).toString(16)); // random string as base for element ids

    // Route-planner
    routePlanner      = false;    // show route-planner?

    // Feature flags for testing
    enableSorting     = false;    // sortable sidepanel?

    // Setting 'preset' will call up those settings from the hash defined here

    static presets = {
        "county":   { sidepanel: 'closed_mobile', showPOISidepanel: true, showRouteSidepanel: true , filterPanel: 'closed', showRouteFilter: true, allRoutes: false },
        "area":     { sidepanel: 'closed_mobile', showPOISidepanel: true, showRouteSidepanel: true , filterPanel: 'closed', showRouteFilter: true, allRoutes: false },
        "route":    { sidepanel: 'closed_mobile', showPOISidepanel: true, showRouteSidepanel: false, filterPanel: 'closed', showRouteFilter: false, elevation: true },
        "poi":      { sidepanel: 'closed',        showPOISidepanel: true, showRouteSidepanel: false, filterPanel: false,    showIdeas: false, showSearch: false },
        "poi_single":{sidepanel: false,           showPOISidepanel: false,showRouteSidepanel: false, filterPanel: false,    showIdeas: false, showSearch: false, showPOIs: false, loadPOIs: false },
        "location": { sidepanel: 'closed_mobile', showPOISidepanel: true, showRouteSidepanel: true , filterPanel: 'closed', showIdeas: false, showSearch: false, allRoutes: false, radius: 0.5, preferFlagship: false, preferRoutes: false, panToResults: false },
        "hub":      { sidepanel: 'closed_mobile', showPOISidepanel: true, showRouteSidepanel: true , filterPanel: 'closed', showIdeas: false, showSearch: false, allRoutes: false, radius: 0.5, preferFlagship: false, preferRoutes: false, panToResults: false },
        "multiple":    { sidepanel: 'closed_mobile', showPOISidepanel: false,showRouteSidepanel: true , filterPanel: 'closed', showIdeas: false, showSearch: false, allRoutes: false, showRouteFilter: false, elevation: 'closed' },
        "specified":{ sidepanel: 'closed_mobile', showPOISidepanel: true, showRouteSidepanel: true , filterPanel: 'closed', showIdeas: false, showSearch: false, showGroups: true, showEvents: true, allRoutes: false, showRouteFilter: false, loadPOIs: false, elevation: 'closed', enableSorting: true },
        "routeplanner": { routePlanner: true, sidepanel: false, filterPanel: false, showRoutes: false, showIdeas: false, showSearch: false, showPOIs: false, loadPOIs: false, elevation: 'closed' },
        "campaigns":{ 
            showPOISidepanel: 'closed_mobile', sidepanel: 'closed_mobile', filterPanel: 'closed_mobile',
            poiMinZoom: 5, scrollproof: true, enableSorting: true,
            preferences: { showCampaigns: true, canGroups: true, canReps: true },
            restrictTo: ['can_group','can_rep'],
            showCampaigns: true, showCampaignFilter: true, showLayerFilter: true,
            showIdeas: false, showPOIFilter: false, showPOIs: false,
            showRouteFilter: false,    showRoutes: false, showSearch: false, allRoutes: false,
        }
    }
    constructor(opts = {}) {
        // Use a preset
        if (opts.preset) {
            const d = MapConfig.presets[opts.preset] || {};
            for (const k in d) { opts[k] = d[k]; }
            delete opts.preset;
        }
        // Copy attributes across, unescaping any &amp;s we find
        for (const k in opts) {
            if (k=='preset' || k=='preferences') continue;
            let v = opts[k];
            if (typeof v=='string') {
                this[k] = v.replace("&amp;","&");
            } else if (Array.isArray(v)) {
                this[k] = v.map(w => (typeof w == 'string') ? w.replace("&amp;","&") : w);
            } else {
                this[k] = v;
            }
        }
        // Implications
        if (this.routeID) { this.showRouteFilter = false; this.searchRoutes = false; this.showIdeas = false; }
        if (this.multiple) { this.allRoutes = true; }
        // Remember preferences for applyPreferences
        this.preferences = opts.preferences;
    }
}
```
