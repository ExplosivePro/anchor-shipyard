<img src="https://amchorcms-assets.s3.amazonaws.com/Anchor+CMS-black.png">

# Cape & Bay Shipyard

Aside from being the primary meeting place of Cape & Bay employees, the Shipyard is 
an in-development package for Laravel, complete with routes, controllers, models, migrations 
and a service provider. Designed to connect Cape & Bay projects to the AnchorCMS and 
vice-versa. Requires Version 3 of the 
[AnchorCMS](https://bitbucket.org/capeandbaytrufit/anchorcms-v3).

This README is for version 3.x of the 
[AnchorCMS](https://bitbucket.org/capeandbaytrufit/anchorcms-v3), which is 
implemented to work with Cape & Bay-produced Projects for PHP and Laravel 6, 7 and beyond.


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
There are two ways to utilize this package -

1. Anchor-2-Project - This flow utilizes the built in routes and controllers bundled into the package. It adds routes under the 
/anchor-cms subdirectory, permitting Anchor to communicate with the project, thus allowing the ability to "extend" AnchorCMS functionality by standardizing the way similar features are
access from project to project by using one integration for all projects, this one. 
A common use-case is when a project 
is a mobile app's API that supports push-notifications. This package contains everything needed to allow
Anchor to fire push notification requests to the project right out of the box with minimal configuration.
  
2. Project-2-Anchor - This flow permits the Project to communicate with Anchor in the form of
an expressive library of classes objects that communicate with Anchor and allow for usage
in a programatic way. Some features allow the Shipyard class (or bundled facade) to be used to retrieve special responses.
For example, if a project utilizes KPI Ad Spend Reports, this developer could use the Market and Budget classes directly,
or use the Shipyard class to call Anchor for data and populate a collection of Markets pre-fillled with Budget data from 
the Anchor Server.

### Anchor-2-Project
#### Routes
All routes live at <project-name>/api/anchor-cms. Except where, noted, all routes
return a JSON-formatted response and do not currently require any validation
to ensure requests come from Anchor. (this will change in the future)

#### Push Notifications
##### feature/notifications/push/filters - GET - Get the filters to be used in the AnchorCMS push-notification manager
##### feature/notifications/push/users - POST - Get list of users and push tokens to be used in the AnchorCMS push-notification manager
##### feature/notifications/push/fire - POST - Fires push notifications to a payload of project-scoped users (currenly only supports Expo. Apple and Firebase coming soon.)

#### Key-Performance Indicators
##### feature/reports/kpi/ - POST - Generate KPI Reports (requires project-side custom logic)


### Project-2-Anchor



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
