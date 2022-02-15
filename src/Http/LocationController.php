<?php

namespace Jiejunf\LaravelLocations\Http;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jiejunf\LaravelLocations\Location;

class LocationController extends Controller
{
    public function countries(Request $request)
    {
        $countries = collect(Location::getCountries($request->get('lang')));

        if ($sortBy = $request->query('sort_by')) {
            $topping = config('locations.topping', []);
            $top = $countries->only($topping);
            $countries = $countries->forget($topping);
            $sortOption = ($request->query('sort_option', 'REGULAR') == 'STRING' and !in_array('languages', (array)$sortBy)) ?
                SORT_STRING : SORT_REGULAR;
            $countries = $countries->sortBy($sortBy, $sortOption, $request->query('descending', false));
            $countries = $top->concat($countries);
        }

        $countries = $countries->values();

        if (!is_null($request->get('page'))) {
            $countries = $countries->forPage($request->get('page'), $request->get('per_page', 30));
        }

        try {
            return config('locations.api_resources.country')::collection($countries);
        } catch (\Exception $e) {
            return $countries;
        }
    }

}