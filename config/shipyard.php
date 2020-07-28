<?php

return [
    'push_notifications' => [
        'enabled' => env('ENABLE_ANCHOR_PUSH_NOTES', false),
        'notifiable_model' => CapeAndBay\Shipyard\PushNotifiables::class,
        // The Model Schema that are filterable
        'notifiable_model_filters' => [],
        // Currently supported - expo, firebase, & none
        'drivers' => ['none']
    ]
];
