<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Blog API

Blog API is testing app

First in `.env` file enter credentials for database and email access:

* DB_CONNECTION=mysql
* DB_HOST=mysql
* DB_PORT=3306
* DB_DATABASE=laravel_blog_api
* DB_USERNAME=root
* DB_PASSWORD=pass

To run app type: `docker-compose -f docker-compose.yaml build && docker-compose up -d`

After deploy is finished type: `docker-compose run --rm artisan migrate` && `docker-compose run --rm artisan passport:install`

To run tests type: `docker-compose run --rm artisan test`

Additional Swagger documentation for endpoints is added, if you want to access it type: `docker-compose run --rm artisan l5-swagger:generate`
and hit http://localhost:483/api/documentation
