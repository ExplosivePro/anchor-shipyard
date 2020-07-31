<?php

namespace CapeAndBay\Shipyard\Controllers\Features\Reports;

use Illuminate\Http\Request;
use CapeAndBay\Shipyard\Controllers\Controller;

class KPIReportShipyardController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get_report()
    {
        $results = ['success' => false, 'reason' => 'Feature Not Activated'];

        $enabled = config('shipyard.reports.kpi.enabled');
        if($enabled === true)
        {
            try
            {
                $reporting_class = config('shipyard.reports.kpi.generator_class');
                $action = new $reporting_class();
                $generate = config('shipyard.reports.kpi.generator_method');

                $data = $this->request->all();

                if(array_key_exists('budgets', $data))
                {
                    if(array_key_exists('markets', $data))
                    {
                        $report = $action->$generate($data['budgets'], $data['markets']);

                        if($report)
                        {
                            $results = ['success' => true, 'report' => $report];
                        }
                        else
                        {
                            $results['reason'] = 'Unable to Generate Report';
                        }
                    }
                    else
                    {
                        $results['reason'] = 'Missing Market Data';
                    }
                }
                else
                {
                    $results['reason'] = 'Missing Budget Data';
                }
            }
            catch(\Exception $e) {
                $results = ['success' => false, 'reason' => 'Error - '.$e->getMessage()];
            }
        }

        return response()->json($results);
    }
}
