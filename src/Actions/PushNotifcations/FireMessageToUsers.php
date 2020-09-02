<?php

namespace CapeAndBay\Shipyard\Actions\PushNotifications;

use Illuminate\Support\Facades\Validator;

class FireMessageToUsers
{
    protected $notifiable;

    public function __construct()
    {
        $model = config('shipyard.push_notifications.notifiable_model');
        $this->notifiable = new $model();
    }

    public function execute($payload = [])
    {
        $results = false;



        return $results;
    }
}
