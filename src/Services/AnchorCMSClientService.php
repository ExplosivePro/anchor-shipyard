<?php

namespace CapeAndBay\Shipyard\Services;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Log;

class AnchorCMSClientService
{
    protected $root_url;
    protected $public_url = '/api';
    protected $client_id;

    public function __construct()
    {
        $this->root_url = env('ANCHOR_CMS_URL','https://anchor.capeandbay.com');
        $this->client_id = config('shipyard.deets.client_uuid');
    }

    public function public_url()
    {
        return $this->root_url.$this->public_url;
    }

    public function get($endpoint)
    {
        $results = false;

        $url = $endpoint;
        $response = Curl::to($url)
            ->asJson(true)
            ->get();

        if($response)
        {
            Log::info('AnchorCMS Response from '.$url, $response);
            $results = $response;
        }
        else
        {
            Log::info('AnchorCMS Null Response from '.$url);
        }

        return $results;
    }

    public function post($endpoint, $args = [], $headers = [])
    {
        $results = false;

        $url = $endpoint;

        if(!empty($args))
        {
            if(!empty($headers))
            {
                $response = Curl::to($url)
                    ->withHeaders($headers)
                    ->withData($args)
                    ->asJson(true)
                    ->post();
            }
            else
            {
                $response = Curl::to($url)
                    ->withData($args)
                    ->asJson(true)
                    ->post();
            }
        }
        elseif(!empty($headers))
        {
            $response = Curl::to($url)
                ->withHeaders($headers)
                ->asJson(true)
                ->post();
        }
        else
        {
            $response = Curl::to($url)
                ->asJson(true)
                ->post();
        }

        if($response)
        {
            Log::info('AnchorCMS Response from '.$url, $response);
            $results = $response;
        }
        else
        {
            Log::info('AnchorCMS Null Response from '.$url);
        }

        if($response)
        {
            Log::info('AnchorCMS Response from '.$url, $response);
            $results = $response;
        }
        else
        {
            Log::info('AnchorCMS Null Response from '.$url);
        }

        return $results;
    }
}
