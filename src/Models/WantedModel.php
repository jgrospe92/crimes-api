<?php

namespace Vanier\Api\Models;

use Vanier\Api\Helpers\WebServiceInvoker;

class WantedModel extends WebServiceInvoker
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getWanted(array $filters = [])
    {

        $uri = 'https://api.fbi.gov/wanted/';
        $items = $this->invokeUri($uri, 'v1/list')['items'];

        $data = [];
        foreach ($items as $key => $item) {
            $data[$key]['title']            = $item['title'];
            $data[$key]['aliases']          = $item['aliases'];
            $data[$key]['description']      = $item['description'];
            $data[$key]['subjects']         = $item['subjects'];
            $data[$key]['field_offices']    = $item['field_offices'];
            $data[$key]['caution']          = $item['caution'];
            $data[$key]['sex']              = $item['sex'];
            $data[$key]['race']             = $item['race'];
            $data[$key]['weight']           = $item['weight'];
            $data[$key]['hair_raw']         = $item['hair_raw'];
            $data[$key]['eyes']             = $item['eyes'];
            $data[$key]['scars_and_marks']  = $item['scars_and_marks'];
            $data[$key]['reward_text']      = $item['reward_text'];
            $data[$key]['status']           = $item['status'];
        }

        return $data;
    }
}