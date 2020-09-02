<?php

namespace CapeAndBay\Shipyard\Actions\PushNotifications;

use CapeAndBay\Shipyard\PushNotifications;
use CapeAndBay\Shipyard\Services\PushNotificationService;

class FireMessageToUsers
{
    protected $notifiable, $notes_model, $notes_service;

    public function __construct(PushNotifications $notes, PushNotificationService $service)
    {
        $model = config('shipyard.push_notifications.notifiable_model');
        $this->notifiable = new $model();
        $this->notes_model = $notes;
        $this->notes_service = $service;
    }

    public function execute($payload = [])
    {
        $results = ['success' => false, 'reason' => 'Failed to Fire Message to User(s)'];

        // make a push notification record
        if($note = $this->notes_model->insertNew($this->prepareNotePayload($payload)))
        {
            // Check for notes type (expo)
            switch($payload['notes_type'])
            {
                case 'expo':
                    unset($payload['notes_type']);
                    if(!array_key_exists('data', $payload))
                    {
                        $payload['data'] = [];
                    }

                    $payload['data']['note_uuid'] = $note->id;


                    if($response = $this->fireExpoMessages($payload, $note))
                    {
                        $results = ['success' => true];
                    }
                    break;

                    default:
            }
        }
        else
        {
            $results['reason'] = 'Could not initialize message.';
        }

        return $results;
    }

    private function prepareNotePayload($payload)
    {
        $data = [
            'msg' => $payload['msg'],
            'notes_type' => $payload['notes_type'],
            'users_sent' => count($payload['users'])
        ];

        if(array_key_exists('title', $payload)) { $data['title'] = $payload['title']; }
        if(array_key_exists('data', $payload)) { $data['data'] = $payload['data']; }
        if(array_key_exists('url', $payload)) { $data['url'] = $payload['url']; }

        return $data;
    }

    private function fireExpoMessages(array $payload, PushNotifications $note)
    {
        $results = false;

        //Bunch up every 100 and queue up the job that will fire the expo job
        $expo_tokens = collect($payload['users']);

        foreach($expo_tokens->chunk(100) as $token_chunk)
        {
            $req_body = [];
            // put together the actual call body
            foreach($token_chunk as $idx => $expo_token)
            {
                $req_body[$idx] = [
                    'to' => $expo_token,
                    'body' => $payload['msg'],
                ];

                if(array_key_exists('title', $payload))
                {
                    $req_body[$idx]['title'] = $payload['title'];
                }
                else
                {
                    $req_body[$idx]['title'] = 'Announcement';
                }

                if(array_key_exists('data', $payload))
                {
                    $req_body[$idx]['data'] = $payload['data'];

                    if(array_key_exists('url', $payload))
                    {
                        $req_body[$idx]['data']['url'] = $payload['url'];
                    }
                }

                if(array_key_exists('msg', $payload))
                {
                    $req_body[$idx]['body'] = $payload['msg'];
                }
            }

            if(config('shipyard.event_sourcing.enabled', false))
            {
                // @todo - if event-sourcing is enabled, fire aggregate
                $results = $this->notes_service->fireEventSourcedExpoMessage($req_body);
            }
            else
            {
                // else - just fire the message - no logging
                $results = $this->notes_service->fireExpoMessage($req_body);
            }
        }

        return $results;
    }
}
