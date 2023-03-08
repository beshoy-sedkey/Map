<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Termwind\Components\Dd;

class MapController extends Controller
{
    public function getMap()
    {
        $countries = [
            'Cairo' => ['lat' => 30.0444, 'lng' => 31.2357],
            'Nairobi' => ['lat' => 1.2921, 'lng' => 36.8219],
            'Dubai' => ['lat' => 25.2048, 'lng' => 55.2708],
            'New York' => ['lat' => 40.7128, 'lng' => 74.0060],
            'Jeddh' => ['lat' => 40.7128, 'lng' => 74.0060]
        ];
        //get data from file
        $file = public_path() . '/file.json';
        $file =   file_get_contents($file);
        $data = json_decode($file, true);
        $data = $data['Entries']['Entry'];
        //mapping on data
        $collection = collect($data)->map(function ($val) use (&$countries) {
            //search on cities
            $city = explode(',', $val['message']);
            for ($i = 0; $i < count($city) ; $i++) {
                $city[$i] = trim($city[$i]);
                if (array_search($city[$i], array_keys($countries))) {
                    $message = str_contains($val['message'],  $city[$i]);
                    if ($message) {
                        $collection = collect($countries[$city[$i]])->put('message', $val['message']);
                        return $collection->all();
                    }
                };
            }
        })->toArray();
        return view('front', ['locations' => $collection]);
    }
}
