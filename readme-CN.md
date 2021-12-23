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

- `GET` countries
  - query:
    - page `int` `可选` 页码.默认不分页.
    - per_page `int` `可选` 每页数,在分页请求中有效.默认 30