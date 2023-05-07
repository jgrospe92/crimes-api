<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Models
use Vanier\Api\Models\WantedModel;

class WantedController extends BaseController
{

    private $wanted_model;

    public function __construct()
    {
        $this->wanted_model = new WantedModel();
    }

    public function handleGetWanted(Request $request, Response $response)
    {
        $data = $this->wanted_model->getWanted();

        return $this->preparedResponse($response, $data);
    }
}