此依赖包为 Laravel 提供地区数据,旗帜和接口.

## 安装

```shell
composer require jiejunf/laravel-locations
```

## 资源

### 发布配置文件

```shell
php artisan vendor:publish --provider="Jiejunf\\LaravelLocations\\LocationProvider"
```

### 创建旗帜目录

包服务启动时总是会将旗帜目录添加到 `config.filesystems.links` 中.

```shell
php artisan storage:link
```

此命令会在 /public 目录创建一个目录的软连接,你应该要在 /.gitignore 中添加这个软连接将它排除.

## 接口

> 在`locations.route`配置有接口的路由前缀. 默认是 `api/locations/`.
> 
> 数据可以在配置 `locations.fix_countries` 中修正.

- `GET` countries
    - query:
        - page `int` `可选` 页码.默认不分页.
        - per_page `int` `可选` 每页数,在分页请求中有效.默认 30
        - sort_by `string|array` `optional` Default iso2
        - descending `bool` `optional` 在 sort_by 是字符型时有效.默认 false
        - sort_option `string` `optional` 针对 phone 排序. 默认 REGULAR.
            - REGULAR
            - STRING

可以使用 API 资源类对接口响应内容进行修改, 默认:

```shell
php artisan make:resource Locations/Country
```

或者在 `locations.api_resources` 中指定

## 升级

### v1

从 Location 类返回的 object 类型全部变为了 array