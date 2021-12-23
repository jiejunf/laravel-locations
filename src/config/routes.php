<?php

use Illuminate\Support\Facades\Route;
use \Jiejunf\LaravelLocations\Http\LocationController;

Route::group([
    'prefix' => config('locations.route'),
], function () {
    Route::get('countries', [LocationController::class, 'countries']);
});
