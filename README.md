# Laravel Sentry Backend

[Sentry 2](https://github.com/cartalyst/sentry) implementation for [Laravel 4](http://laravel.com).

[![Build Status](https://travis-ci.org/djordje/laravel-sentry-backend.png?branch=master)](https://travis-ci.org/djordje/laravel-sentry-backend)

### Early Alpha

##### Installation

Recommended installation is trough `composer`, add to your `composer.json`:

```json

"require": {
	"djordje/laravel-sentry-backend": "dev-master"
}

```

Add service provider to your `app/config/app.php` file:

```php

# ...

'providers' => array(
    # ...
    ### Required packages:
    'Djordje\LaravelTwbsHelpers\LaravelTwbsHelpersServiceProvider',
    'Djordje\LaravelBackendLayout\LaravelBackendLayoutServiceProvider',
    'Djordje\LaravelSentryBackend\LaravelSentryBackendServiceProvider',
),

# ...

```

Publish package configuration to `app/config/packages` if you want to modify it:

```sh

php artisan config:publish djordje/laravel-sentry-backend

```


###### Released under MIT licence