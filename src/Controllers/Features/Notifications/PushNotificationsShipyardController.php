<?php

namespace CapeAndBay\Shipyard\Controllers\Features\Notifications;

use Illuminate\Http\Request;
use CapeAndBay\Shipyard\Controllers\Controller;
use CapeAndBay\Shipyard\Actions\PushNotifications\GetNotifiableUsers;
use CapeAndBay\Shipyard\Actions\PushNotifications\GetNotificationFilters;

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
                $results['reason'] = 'Failed to Get Filters';
            }
        }


        return response()->json($results);
    }
}
