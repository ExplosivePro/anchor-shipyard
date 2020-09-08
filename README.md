<img src="https://amchorcms-assets.s3.amazonaws.com/Anchor+CMS-black.png">
# Cape & Bay Shipyard

Aside from being the primary meeting place of Cape & Bay employees, the Shipyard is 
an in-development package for Laravel, complete routes, controllers, models, migrations 
and a service provider. Designed to connect Cape & Bay projects to the AnchorCMS and 
vice-versa. Requires Version 3 of the 
[AnchorCMS](https://bitbucket.org/capeandbaytrufit/anchorcms-v3).

This README is for version 3.x of the 
[AnchorCMS](https://bitbucket.org/capeandbaytrufit/anchorcms-v3), which is 
implemented to work with Cape & Bay-produced Projects for PHP and Laravel 6, 7 and beyond.

Jump To:
* [Getting Started](_#Getting-Started_)
* [Getting Help](_#Getting-Help_)
* [Contributing](_#Contributing_)
* [More Resources](_#Resources_)

## Getting Started

### Installation

Via Composer

``` bash
$ composer require capeandbay/shipyard
```

The package will automatically register itself.

You can publish the migration with:
```bash
php artisan vendor:publish --provider="CapeAndBay\Shipyard\ShipyardServiceProvider" --tag="migrations"
```

After publishing the migration you can create the `push_notifications` table by running the migrations:


```bash
php artisan migrate
```

You can optionally publish the config file with:
```bash
php artisan vendor:publish --provider="CapeAndBay\Shipyard\ShipyardServiceProvider" --tag="config"
```
### Configuration

By default, the package uses the following environment variables to auto-configure the plugin without modification:
```
ANCHOR_CLIENT_ID (default = '')
ENABLE_ANCHOR_EVENT_SOURCING (default = false)
ENABLE_ANCHOR_PUSH_NOTES (default = false)
DB_CONNECTION (default = mysql)
```

To utilize certain features such as KPI Reports, customizing the file is recommended.

To customize the configuration file, publish the package configuration using Artisan.

```sh
php artisan vendor:publish  --provider="Aws\Laravel\AwsServiceProvider"
```

The settings can be found in the generated `config/shipyard.php` configuration file. .

```php
<?php

return [
    'deets' => [
        'client_uuid' => env('ANCHOR_CLIENT_ID', '')
    ],
    'class_maps' => [
        'ad-budgets'   => \CapeAndBay\Shipyard\Library\AdOps\Budget::class,
        'ad-markets'   => \CapeAndBay\Shipyard\Library\AdOps\Market::class
    ],
    'event-sourcing' => [
        'enabled' => env('ENABLE_ANCHOR_EVENT_SOURCING', false),
        'driver' => 'spatie'
    ],
    'push_notifications' => [
        'enabled' => env('ENABLE_ANCHOR_PUSH_NOTES', false),
        'notifiable_model' => CapeAndBay\Shipyard\PushNotifiables::class,
        'db_connection' => env('DB_CONNECTION', 'mysql'),
        'db_table_name' => 'push_notifications',
        // The Model Schema that are filterable
        'notifiable_model_filters' => [],
        // Currently supported - expo, firebase, & none
        'drivers' => ['none']
    ],
    'reports' => [
        'kpi' => [
            'enabled' => false,
            'generator_class' => null,
            'generator_method' => null
        ],
    ],
];
```

Note that you can always swap out preloaded classes with a project's arbitrary own; 
the Shipyard will use that class in that context.

## Usage

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```



## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email angel@capeandbay.com instead of using the issue tracker.

## Credits

- [Angel Gonzalez][link-author]
- [All Contributors][link-contributors]

## License

Proprietary. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/capeandbay/shipyard.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/capeandbay/shipyard.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/capeandbay/shipyard/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/capeandbay/shipyard
[link-downloads]: https://packagist.org/packages/capeandbay/shipyard
[link-travis]: https://travis-ci.org/capeandbay/shipyard
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/capeandbay
[link-contributors]: ../../contributors
