<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Location\Coordinate;
use Location\Polygon;
use Symfony\Component\Yaml\Yaml;

class DiscoverController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'osmType' => 'required_with:osmId|in:p,w,r,point,way,relation',
            'osmId' => 'required_with:type|numeric'
        ]);

        $lat = $request->get('lat');
        $lng = $request->get('lng');

        $point = new Coordinate($lat, $lng);

        $files = File::glob(storage_path('app/areas/*.geojson'));
        foreach ($files as $fileName) {
            $geofence = $this->loadPolygon($fileName);
            if ($geofence->contains($point)) {

                $area = pathinfo($fileName, PATHINFO_FILENAME);
                $yamlConfigFile = dirname($fileName) . DIRECTORY_SEPARATOR . $area . '.yaml';
                $config = Yaml::parseFile($yamlConfigFile);
                $url = $config['url'];

                if ($path = $this->getOsmCode($request)) {
                    return response()->json([
                            'url' => $url . $path,
                            'dataUrl' => $url . 'json/' . $path,
                            'area' => $area,
                        ]
                    );
                }

                return response()->json([
                        'baseUrl' => $url,
                        'dataBaseUrl' => $url . 'json/',
                        'area' => $area,
                    ]
                );
            }

        }

        return response()->make('', 204);
    }

    private function loadPolygon($fileName)
    {
        $geofence = new Polygon();

        $data = json_decode(file_get_contents($fileName));
        foreach ($data->geometry->coordinates[0] as $point) {
            $geofence->addPoint(new Coordinate($point[1], $point[0]));
        }
        return $geofence;
    }

    private function getOsmCode(Request $request)
    {
        if ($request->get('osmId') && $request->get('osmType')) {
            return $request->get('osmType')[0] . $request->get('osmId');
        }
    }
}
