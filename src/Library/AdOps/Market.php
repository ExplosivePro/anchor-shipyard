<?php

namespace CapeAndBay\Shipyard\Library\AdOps;

use CapeAndBay\Shipyard\Library\Feature;

class Market extends Feature
{
    protected $url = '/budgets/market';

    protected $market = '';
    protected $budgets = [];
    protected $total_spend = 0, $fb_spend = 0, $google_spend = 0;
    public const ROI3 = 108;
    public const ROI12 = 360;

    public function __construct($name, $budgets = [])
    {
        parent::__construct();
        $this->market = $name;
        $this->budgets = $budgets;

        $this->setupTotals();
    }

    public function market_url()
    {
        return $this->anchorCMS_client->public_url().$this->clients_uri().$this->url;
    }

    private function setupTotals()
    {
        if(count($this->budgets) > 0)
        {
            foreach($this->budgets as $club_id => $budget)
            {
                $this->total_spend += $budget->getSpend('total');
                $this->fb_spend += $budget->getSpend('fb');
                $this->google_spend += $budget->getSpend('google');
            }
        }
    }

    private function calculateROI(int $memberships_sold, $spend, $type = 'total', $time = 3)
    {
        if(($time == 3) || ($time == 12))
        {
            try
            {
                $roi_amt = ($time == 3) ? self::ROI3 : self::ROI12;
                switch($type)
                {
                    case 'total':
                        $this->total_spend = $spend;
                        $results = ($memberships_sold * $roi_amt) / $this->total_spend;
                        break;

                    case 'fb':
                        $this->fb_spend = $spend;
                        $results = ($memberships_sold * $roi_amt) / $this->fb_spend;
                        break;

                    case 'google':
                        $this->google_spend = $spend;
                        $results = ($memberships_sold * $roi_amt) / $this->google_spend;
                        break;
                    default:
                        $results = false;

                }
            }
            catch(\Exception $e)
            {
                $results = false;
            }
        }
        else
        {
            $results = false;
        }

        return $results;
    }

    public function calculateSpend($type = 'total')
    {
        $spend = 0;

        foreach ($this->budgets as $budget) {
            $spend += $budget->getSpend($type);
        }

        return $spend;
    }

    public function calculate3MonthROI(int $memberships_sold, $type = 'total')
    {
        return $this->calculateROI($memberships_sold, $this->calculateSpend($type), $type, 3);
    }

    public function calculate12MonthROI(int $memberships_sold, $type = 'total')
    {
        return $this->calculateROI($memberships_sold, $this->calculateSpend($type), $type, 12);
    }

    public function getMarketName()
    {
        return $this->market;
    }

    public function getBudgets()
    {
        return $this->budgets;
    }
}
