<?php

return [
    'deets' => [
        'client_uuid' => env('ANCHOR_CLIENT_ID', '')
    ],
    'class_maps' => [
        'ad-budgets'   => \CapeAndBay\Shipyard\Library\AdOps\Budget::class,
        'ad-markets'   => \CapeAndBay\Shipyard\Library\AdOps\Market::class
    ],
    'push_notifications' => [
        'enabled' => env('ENABLE_ANCHOR_PUSH_NOTES', false),
        'notifiable_model' => CapeAndBay\Shipyard\PushNotifiables::class,
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
