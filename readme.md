[中文](readme-CN.md)

This package provide location data, flag and api for laravel.

## install

```shell
composer require jiejunf/laravel-locations
```

## resource

### publish config

```shell
php artisan vendor:publish --provider="Jiejunf\\LaravelLocations\\LocationProvider"
```

### make flags dir

Package service provider always sets flags-path into `config.filesystems.links` on boot.

```shell
php artisan storage:link
```

This script will make a symbol link in /public. You should add it into /.gitignore .

## API

> api routes have a prefix, setting in `locations.route`. default is `api/locations/`.
> 
> Data can be corrected in the configuration `locations.fix_countries`.

- `GET` countries
    - query:
        - page `int` `optional` page number. Default no paging.
        - per_page `int` `optional` per page count. Default 30
        - sort_by `string|array` `optional` Default iso2
        - descending `bool` `optional` active if sort_by is string. Default false
        - sort_option `string` `optional` for phone. Default REGULAR.
            - REGULAR
            - STRING

The API resource class can be used to modify the response content. default:

```shell
php artisan make:resource Locations/Country
```

Or specify in `locations.api_resources`

## upgrade

### from v1

All object types returned from the Location class are changed to array
