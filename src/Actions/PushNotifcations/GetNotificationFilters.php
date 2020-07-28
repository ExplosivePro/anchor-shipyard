<?php

namespace CapeAndBay\Shipyard\Actions\PushNotifications;

use CapeAndBay\Shipyard\PushNotifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetNotificationFilters
{
    protected $filter_defs, $notifiable;

    public function __construct()
    {
        $model = config('shipyard.push_notifications.notifiable_model');
        $this->notifiable = new $model();
        $this->filter_defs = config('shipyard.push_notifications.notifiable_model_filters', []);
    }

    public function execute()
    {
        $results = false;

        if(count($this->filter_defs) > 0)
        {
            $results = [];

            foreach($this->filter_defs as $filter => $def)
            {
                if(array_key_exists('type', $def))
                {
                    switch($def['type'])
                    {
                        case 'list':

                            $relation = false;
                            if(array_key_exists('relation', $def))
                            {
                                $relation = $def['relation'];
                            }

                            if($relation)
                            {
                                Log::info('Looking up '.$filter);
                                $queried = $this->notifiable->select(DB::raw("DISTINCT $filter"))->with($relation)->get();

                                if(count($queried) > 0)
                                {
                                    $results[$filter] = [];
                                    foreach ($queried as $idx => $record)
                                    {
                                        if(!array_key_exists($record->$filter, $results[$filter]))
                                        {
                                            Log::info('Checking model - '. $record->$filter);
                                            $col = $def['column'];
                                            $rel = $def['relation'];

                                            if(!is_null($record->$rel))
                                            {
                                                $results[$filter][$record->$filter] = $record->$rel->$col;
                                            }
                                            else
                                            {
                                                Log::info('Skipping record, its relation is null - '. $record->$filter);
                                            }
                                        }
                                    }

                                }

                            }
                            else
                            {
                                Log::info('Looking up '.$filter);
                                $queried = $this->notifiable->select(DB::raw("DISTINCT $filter"))->get();

                                if(count($queried) > 0)
                                {
                                    $results[$filter]  = [];
                                    foreach ($queried as $idx => $record)
                                    {
                                        if(!array_key_exists($record->$filter, $results))
                                        {
                                            $col = $def['column'];

                                            if(!is_null($record->$col))
                                            {
                                                $results[$filter][$record->$filter] = $record->$col;
                                            }
                                            else
                                            {
                                                Log::info('Skipping record, its value is null - '. $record->$filter);
                                            }
                                        }
                                    }
                                }
                            }
                            break;

                        default:
                    }
                }
            }
        }

        return $results;
    }
}
