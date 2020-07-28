<?php

namespace CapeAndBay\Shipyard\Actions\PushNotifications;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use CapeAndBay\Shipyard\PushNotifiables;

class GetNotifiableUsers
{
    protected $filter_defs, $notifiable;

    public function __construct()
    {
        $model = config('shipyard.push_notifications.notifiable_model');
        $this->notifiable = new $model();
        $this->filter_defs = config('shipyard.push_notifications.notifiable_model_filters', []);
    }

    public function execute($filters = [])
    {
        $results = false;

        if(array_key_exists('notes_type', $filters))
        {
            $note_type = $filters['notes_type'];
            unset($filters['notes_type']);
            switch($note_type)
            {
                case 'expo':
                    $records = $this->notifiable->whereNotNull('expo_push_token');

                    if(count($filters['filters']) > 0)
                    {
                        foreach($filters['filters'] as $filter => $val)
                        {

                            $records = $records->where($filter, '=', $val);
                        }
                    }

                    $records = $records->get();
                    break;

                case 'firebase':
                default:
                    $records = [];
            }

            $results = [];
            if(count($records) > 0)
            {
                $results = $records->toArray();
            }
        }

        return $results;
    }
}
