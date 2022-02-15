<?php

namespace Jiejunf\LaravelLocations;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class LocationProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/locations.php' => config_path('locations.php'),
        ]);

        $flagSymbol = config('locations.flags_link');
        if (!is_null($flagSymbol)) {
            Config::set('filesystems.links.' . public_path($flagSymbol), base_path('vendor/components/flag-icon-css/flags'));
        }

        if (config('locations.enable_route')) {
            $this->loadRoutesFrom(__DIR__ . '/config/routes.php');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'locations.php', 'locations');
    }
}