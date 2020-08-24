<?php

namespace CapeAndBay\Shipyard\Services;

class LibraryService
{
    public function __construct()
    {

    }

    public function retrieve($feature = '', $option = null)
    {
        $results = false;

        switch($feature)
        {
            case 'ad-budgets':
                $results = $this->getClientBudget($option);
                break;

            default:
                $results = $this->basicLoadObj($feature);
        }

        return $results;
    }

    public function basicLoadObj($name)
    {
        try
        {
            $port_model_name = config('shipyard.class_maps.'.$name);

            $results = new $port_model_name();
        }
        catch(\Exception $e)
        {
            $results = true;
        }

        return new $results;
    }

    public function getClientBudget($date = null)
    {
        $results = false;

        $budget_model_name = config('shipyard.class_maps.ad-budgets');
        $budget_model = new $budget_model_name();
        if(!is_null($date))
        {
            $response = $budget_model->anchorCMS_client->get($budget_model->budgets_url()."?date={$date}");
        }
        else
        {
            $response = $budget_model->anchorCMS_client->get($budget_model->budgets_url());
        }


        if($response && array_key_exists('success', $response) && ($response['success'] == true))
        {
            $results = [];

            // iterate through the markets in the $response and a init an array of Budgets
            foreach($response['markets'] as $market_name => $market_data)
            {
                $budgets = [];
                foreach($market_data['budgets'] as $budget_arr)
                {
                    $budgets[$budget_arr['club_id']] = new $budget_model_name($budget_arr);
                }

                // Create a market object and pass in the Budget array and name
                $market_model_name = config('shipyard.class_maps.ad-markets');
                $results[$market_name] = new $market_model_name($market_name, $budgets);
            }
        }

        return $results;
    }
}
