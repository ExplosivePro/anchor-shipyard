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

        /**
         * STEPS
         * @todo - Make Push Notifications migration and Model
         * 1. make a push notification record
         * 2. Check for notes type (expo)
         * 3. Bunch up every 100 and queue up the job that will fire the expo job
         * 4. Return true
         */

        return $results;
    }
}
