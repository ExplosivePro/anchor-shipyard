<?php

Route::group([
    'namespace'  => 'CapeAndBay\Shipyard\Controllers',
    'middleware' => 'api',
    'prefix' => 'api'
], function () {
    Route::group(['prefix' => 'anchor-cms'], function () {
        Route::group(['prefix' => 'feature'], function () {
            Route::group(['prefix' => 'notifications'], function () {
                Route::group(['prefix' => 'push'], function () {
                    Route::get('/filters', 'Features\Notifications\PushNotificationsShipyardController@get_filters');
                    Route::post('/users', 'Features\Notifications\PushNotificationsShipyardController@get_users');
                });
            });
        });
    });
});
