<?php

return [
    'enable_route' => true,
    'route' => 'api/locations',

    'enable_emoji' => true,

    'enable_flags' => true,
    'flags_link' => 'location_flags',

    'topping' => [
//        'CN'
    ],

    'fix_countries' => [
        'AN' => ['phone' => 'N/A'],
        'AQ' => ['phone' => '6721'],
        'BV' => ['phone' => '47'],
        'GS' => ['phone' => '500'],
        'HM' => ['phone' => '1672'],
        'TF' => ['phone' => 'N/A'],
    ],

    'api_resources' => [
        'country' => '\App\Http\Resources\Locations\Country',
    ],
];