<?php

namespace Jiejunf\LaravelLocations\Http;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\MergeValue;
use Illuminate\Routing\Controller;
use Jiejunf\LaravelLocations\Location;

class LocationController extends Controller
{
    public function countries(Request $request)
    {
        $countries = collect(Location::getCountries($request->get('lang')));
        $topping = config('locations.topping', []);
        $topCountries = collect();
        foreach ($topping as $top) {
            $topCountries[$top] = $countries->pull($top);
        }
        $countries = $topCountries->merge($countries);

        if (!is_null($request->get('page'))) {
            $countries = $countries->forPage($request->get('page'), $request->get('per_page', 30));
        }

        $outputs = [];
        $enable_flags = config('locations.enable_flags');
        $flags_dir = rtrim(config('locations.flags_link'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $enable_emoji = config('locations.enable_emoji');

        foreach ($countries as $iso2 => $country) {
            $ex = ['iso2' => $iso2];
            if ($enable_emoji) {
                $ex['emoji'] = Location::getEmoji($iso2);
            }
            if ($enable_flags) {
                $iso2Lower = strtolower($iso2);
                $ex['flag'] = asset($flags_dir . '4x3' . DIRECTORY_SEPARATOR . $iso2Lower . '.svg');
                $ex['flag_1x1'] = asset($flags_dir . '1x1' . DIRECTORY_SEPARATOR . $iso2Lower . '.svg');
            }
            $outputs[] = $ex + $country;
        }
        return $outputs;
    }
}