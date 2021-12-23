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

- `GET` countries
    - query:
        - page `int` `optional` page number. Default no paging.
        - per_page `int` `optional` per page count. Default 30

