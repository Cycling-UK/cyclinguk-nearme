## Search API

https://cycling-uk.cycle.travel/query?*<the location request\>*&content=*pois,events,groups,routes*

The location request can be:

* bounds=*\<w,s,e,n\>*
* lat=*\<num\>*&lon=*\<num\>*&radius=*\<num\>*
* route=*\<drupal_id\>*&radius=*\<num\>*
* route_name=*\<string\>*&radius=*\<num\>*
* area=*\<drupal_uuid\>*
* area_name=*\<string\>*

And the content can be any of pois,events,groups,routes,areas, comma-separated.

It returns a JSON document with the Drupal node IDs in, which should be fairly self-explanatory.

A few examples:

* All content within the specified bounding box:

  https://cycling-uk.cycle.travel/query?bounds=-1,50,1,52&content=routes,pois,events,groups

* All content within 0.1 degrees of 51.5,-1.5:

  https://cycling-uk.cycle.travel/query?lon=-1.5&lat=51.5&radius=0.1&content=routes,pois,events,groups

* all content within Oxfordshire

  https://cycling-uk.cycle.travel/query?area_name=Oxfordshire&content=routes,pois,events,groups

* Content within 0.1 degrees of the 'Edinburgh all-ability loop' route:

  https://cycling-uk.cycle.travel/query?route_name=Edinburgh%20all-ability%20loop&radius=0.1&content=pois,events,groups

* Content within 0.5 degrees of King Alfred's Way:

  https://cycling-uk.cycle.travel/query?route=6c28b0e1-5db4-4b52-8839-b7371ed6c7b6&radius=0.5&content=pois,events,groups


## Example JSON Data returned from a Search

Search: https://cycling-uk.cycle.travel/query?lon=-3.5&lat=50.5&radius_km=80&content=routes,pois,events,groups,posts

Result:

```json
{
  "status": "ok",
  "results": {
    "routes": [
      "f695c480-878c-4009-8b70-eae6b42446d9",
      "f72bb9b1-b002-475e-9c7e-39f177e02569",
      "34e21b67-571b-4726-a1e1-d8f68c5e34df",
      "a7cb030e-fa31-40c2-a3a3-ae3b0d59059c",
      "ce9e4478-d72b-4176-b9f1-d10a46ffd32f",
      "563deca3-97bf-4dc0-83d7-cc737a5d82c3"
    ],
    "pois": [],
    "events": [],
    "groups": [
      "9da6d008-cd34-4415-bea0-7decfee34a01",
      "33bd0212-0a82-436d-a1af-0a08f8d75074",
      "139a56e4-157d-4027-936a-36b6a1a52d06",
      "f5aa9523-ee2e-457b-9a47-92a9226ba66d",
      "9684df73-bc2c-433e-a998-9119c7ed8170",
      "0bf71ef7-534c-486e-a43b-a3eec1668901",
      "2b05c57c-e28e-42a0-84d6-f2eb74c6beba"
    ],
    "posts": [
      "ddf9d145-56b0-4dad-879a-5ce0149b4d6a",
      "5a7648c8-7984-413c-ad29-38741670485e"
    ]
  }
}
```
## Example Autocomplete response data

```json
{
  "type": "Place",
  "name": "Worthing",
  "value": "Worthing",
  "location": [
  -0.3699697,
  50.8115402
],
  "properties": {
  "osm_id": 117548,
    "osm_type": "R",
    "extent": [
    -0.4465567,
    50.8632589,
    -0.3302723,
    50.8017364
  ],
    "country": "United Kingdom",
    "osm_key": "place",
    "countrycode": "GB",
    "osm_value": "town",
    "name": "Worthing",
    "county": "West Sussex",
    "state": "England",
    "type": "city"
},
  "__autocomplete_id": 0
}

```

## Example node data from Drupal

```json
{
  "contentType": "Event\/Ride",
  "title": "CTC Cambridge Sunday rides",
  "audaxCategory": "",
  "body": "\u003Cp\u003ECTC Cambridge usually has one or two rides on a Sunday.\u00a0 Check our website for details of rides:\u00a0distance,\u00a0 destination, start time\u00a0etc. and any ride registration details.\u00a0 Rides generally start from Brookside in Cambridge at either 9.00am or 9.30am, but do always check the CTC Cambridge website\u00a0\u003Ca href=\u0022https:\/\/ctccambridge.org.uk\/\u0022\u003Ehttps:\/\/ctccambridge.org.uk\/\u003C\/a\u003E for details of the current week\u0026#039;s rides.\u00a0 We look forward to meeting you on one of our rides.\u003C\/p\u003E\n",
  "eventDate": "1685865600",
  "geofield": "",
  "startLocation": "POINT(0.131273 52.195079)",
  "image": "https:\/\/experience.cyclinguk.org\/sites\/default\/files\/styles\/16_9_sm_cuk_breakpoint_sm\/public\/2022-11\/screenshot_2022-01-18_at_14.54.49.png?itok=F2S_EnGJ",
  "rideType": "",
  "eventType": "Group ride",
  "d7SiteUuid": "b8f6a40f-caf0-47ea-942e-279102ef33ee",
  "sticky": "Off",
  "lastUpdated": "1669142298",
  "uuid": "4ac07a96-b7e7-4a87-8c80-af12c718bbd2",
  "d7SiteUrl": "https:\/\/www.cyclinguk.org\/event\/ctc-cambridge-thursday-rides",
  "series": "",
  "surface": "",
  "nodeLink": "https:\/\/experience.cyclinguk.org\/node\/37366",
  "experienceContent": "",
  "groupActivities": "",
  "address": "",
  "amenities": "",
  "routeFeatures": "",
  "gpxFile": "",
  "routeDistance": "",
  "rideLevel": "",
  "poiType": "",
  "strava": "",
  "teaser": "",
  "telephone": "",
  "groupType": "",
  "website": "",
  "youTube": "",
  "img": "",
  "gpxFilesD7": "",
  "circularRoute": "",
  "d7BikeType": "",
  "finishAddress": "",
  "d7RideLevel": "",
  "startAddress": "",
  "lengthKm": "",
  "lengthMiles": "",
  "routeClassification": "",
  "locationData": "",
  "concealFromGeneralMaps": "",
  "tags": "",
  "downloadLink": "",
  "groupSite": "",
  "facebook": "",
  "instagram": "",
  "twitter": ""
}
```
