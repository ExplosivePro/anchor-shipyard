<?php

namespace CapeAndBay\Shipyard\Services;

use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class PushNotificationService
{
    protected $push_method;
    protected $expo_api_url = 'https://exp.host/--/api/v2/push/send';

    public function __construct(string $method = '')
    {
        if(empty($method))
        {
            $this->push_method = config('shipyard.push_notifications.driver', 'none');
        }
        else
        {
            $this->push_method = $method;
        }
    }

    public function fireEventSourcedExpoMessage($payload): bool
    {
        $results = false;

        // @todo - call the Aggregate and return true

        return $results;
    }

    public function fireExpoMessage($payload): bool
    {
        $results = false;

        $headers = [
            'host' => 'exp.host',
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'accept-encoding' => 'gzip, deflate'
        ];

        $response = Curl::to($this->expo_api_url)
            //->withHeaders($headers)
            ->withData($payload)
            ->asJson(true)
            ->post();

        if(array_key_exists('errors', $response))
        {
            foreach ($response['errors'] as $idx => $error)
            {
                if(array_key_exists('details', $error))
                {
                    foreach ($error['details'] as $project => $tokens)
                    {
                        // Log errors in log.
                        Log::error($error['details']);
                        Log::error($project);
                        Log::error($tokens);
                    }
                }
            }
        }
        else
        {
            $results = true;
        }

        return $results;
    }
}
