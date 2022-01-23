# OpenPlaceGuide - Discover API

OpenPlaceGuide is federated business directory.

The discover component is a [Laravel Lumen 8](https://lumen.laravel.com/docs/8.x/) project which provides an API to
match given coordinates to the OpenPlaceGuide instance.

## How it works

The discover process is a two step process.

You need the location (latitude, longitude) and optional the osmType (point, way, relation) and osmId (positive integer).

Example (*Remark*: API not yet hosted online):

`GET https://discover.openplaceguide.org/v1/discover?lat=12.78&lng=36.92&osmType=point&osmId=123456`

returns:

```json
{
    "url": "https:\/\/opg.addismap.com\/p123456",
    "dataUrl": "https:\/\/opg.addismap.com\/json\/p123456",
    "area": "ethiopia"
}
```

The returned url will redirect to the micro page for the OSM object.

The returned data URL can be fetched to obtain basic information, such as the logo image and a media gallery (if present).

The parmeters `osmType`  and `osmId` are optional, if omitted, only the base URL to the federated OpenPlaceGuide instance 
for the area fill be reported.

## Add your country

Active contributors / teams of the OpenStreetMap are invited to set up a data repository for their country and register
the country in here.

1. Supply your `.poly` file, for example from download.openstreetmap.fr or GeoFabrik

2. Convert the `.poly` file to a geojson file
   ```bash
   git clone https://github.com/spatialoperator/osmosis2geojson.git
   cd osmosis2geojson
   npm install osmosis2geojson
   ./osmosis2geojson.js your-country.poly  > ../discover/storage/app/areas/your-country.geojson
   ```

3. Add the `.yaml` config file in storage/app/areas/your-country.yaml

4. The config file has to validate against `storage/app/areas/area.schema.json`, this will be checked when submitting
   the pull request.



