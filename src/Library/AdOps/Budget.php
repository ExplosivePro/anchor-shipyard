<?php

namespace CapeAndBay\Shipyard\Library\AdOps;

use Ixudra\Curl\Facades\Curl;
use CapeAndBay\Shipyard\Library\Feature;

class Budget extends Feature
{
    protected $url = '/budgets';
    protected $club_id,
        $fb_budget = 0,
        $google_budget = 0,
        $total_spend = 0,
        $fb_spend = 0,
        $google_spend = 0;
    
    public const ROI3 = 108;
    public const ROI12 = 360;
    private $uuid;

    public function __construct($data = [])
    {
        parent::__construct();

        if(!empty($data))
        {
            $this->uuid = $this->setIfExists('uuid', $data);
            $this->club_id = $this->setIfExists('club_id', $data);
            $this->fb_budget = $this->setIfExists('facebook_ig_budget', $data);
            $this->google_budget = $this->setIfExists('google_budget', $data);
            $this->total_spend = $this->setIfExists('spend-total', $data);
            $this->google_spend = $this->setIfExists('spend-google', $data);
            $this->fb_spend = $this->setIfExists('spend-fb', $data);
        }
    }

    public function budgets_url()
    {
        return $this->anchorCMS_client->public_url().$this->clients_uri().$this->url;
    }

    private function calculateROI(int $memberships_sold, $type = 'total', $time = 3)
    {
        if(($time == 3) || ($time == 12))
        {
            try
            {
                $roi_amt = ($time == 3) ? self::ROI3 : self::ROI12;
                switch($type)
                {
                    case 'total':

                        $results = ($memberships_sold * $roi_amt) / $this->total_spend;
                        break;

                    case 'fb':
                        $results = ($memberships_sold * $roi_amt) / $this->fb_spend;
                        break;

                    case 'google':
                        $results = ($memberships_sold * $roi_amt) / $this->google_budget;
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

    public function calculate3MonthROI(int $memberships_sold, $type = 'total')
    {
        return $this->calculateROI($memberships_sold, $type, 3);
    }

    public function calculate12MonthROI(int $memberships_sold, $type = 'total')
    {
        return $this->calculateROI($memberships_sold, $type, 12);
    }

    public function getBudget($type = 'total')
    {
        switch($type)
        {
            case 'total':
                $results = $this->fb_budget + $this->google_budget;
                break;

            case 'fb':
                $results = $this->fb_budget;
                break;

            case 'google':
                $results = $this->google_budget;
                break;
            default:
                $results = false;
        }

        return $results;
    }

    public function getSpend($type = 'total')
    {
        switch($type)
        {
            case 'total':
                $results = $this->total_spend;
                break;

            case 'fb':
                $results = $this->fb_spend;
                break;

            case 'google':
                $results = $this->google_spend;
                break;
            default:
                $results = false;
        }

        return $results;
    }

    public function getClubId()
    {
        return $this->club_id;
    }
}
