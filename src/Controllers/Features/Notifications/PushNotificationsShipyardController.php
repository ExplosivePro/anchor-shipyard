<?php

namespace CapeAndBay\Shipyard\Controllers\Features\Notifications;

use CapeAndBay\Shipyard\Actions\PushNotifications\FireMessageToUsers;
use Illuminate\Http\Request;
use CapeAndBay\Shipyard\Controllers\Controller;
use CapeAndBay\Shipyard\Actions\PushNotifications\GetNotifiableUsers;
use CapeAndBay\Shipyard\Actions\PushNotifications\GetNotificationFilters;
use Illuminate\Support\Facades\Validator;

class PushNotificationsShipyardController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get_filters(GetNotificationFilters $action)
    {
        $results = ['success' => false, 'reason' => 'Feature Not Activated'];

        // @todo - make the middleware for this.
        if(config('shipyard.push_notifications.enabled'))
        {
            if($filters = $action->execute())
            {
                $results = ['success' => true, 'filters' => $filters];
            }
            else
            {
                $results['reason'] = 'Failed to Get Filters';
            }
        }


        return response()->json($results);
    }

    public function get_users(GetNotifiableUsers $action)
    {
        $results = ['success' => false, 'reason' => 'Feature Not Activated'];

        // @todo - make the middleware for this.
        if(config('shipyard.push_notifications.enabled'))
        {
            if($users = $action->execute($this->request->all()))
            {
                $results = ['success' => true, 'users' => $users];
            }
            else
            {
                $results['reason'] = 'Failed to Get Users';
            }
        }

        return response()->json($results);
    }

    public function fire_message(FireMessageToUsers $action)
    {
        $results = ['success' => false, 'reason' => 'Feature Not Activated'];

        // @todo - make the middleware for this.
        if(config('shipyard.push_notifications.enabled'))
        {
            $validated = Validator::make($this->request->all(), [
                'users' => 'bail|required|array',
                'msg'   => 'bail|required',
                'notes_type' => 'bail|required',
                'url' => 'required'
            ]);

            if ($validated->fails())
            {
                foreach($validated->errors()->toArray() as $col => $msg)
                {
                    $results['reason'] = $msg[0];
                    break;
                }
            }
            else
            {
                if($action->execute($this->request->all()))
                {
                    $results = ['success' => true];
                }
                else
                {
                    $results['reason'] = 'Failed to Fire Message to User(s)';
                }
            }
        }

        return response()->json($results);
    }
}
