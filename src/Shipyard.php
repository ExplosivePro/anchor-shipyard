<?php

namespace CapeAndBay\Shipyard;

use CapeAndBay\Shipyard\Services\LibraryService;

class Shipyard
{
    protected $library;

    public function __construct(LibraryService $lib)
    {
        $this->library = $lib;
    }

    public function get($feature = '', $option = null)
    {
        $results = false;

        $asset = $this->library->retrieve($feature, $option);

        if($asset)
        {
            $results = $asset;
        }

        return $results;
    }
}