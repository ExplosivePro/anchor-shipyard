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
