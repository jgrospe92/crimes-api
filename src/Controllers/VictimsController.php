<?php

namespace Vanier\Api\Controllers;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\controllers\BaseController;
use Vanier\Api\Models\VictimsModel;
use Vanier\Api\exceptions\HttpErrorHandler;

class VictimsController extends BaseController
{
    private $victims_model = null;

    public function __construct()
    {
        $this->victims_model = new VictimsModel();
    }

    public function handleGetAllVictims(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $victims_model = new VictimsModel();
        $data = $victims_model->handleGetAllVictims($filters);
        return $this->prepareOkResponse($response, $data);
    }

    public function handleGetVictimById(Request $request, Response $response, array $uri_args) {     
        $victim_id = $uri_args ["victim_id"];
        $victims_model = new VictimsModel();
        $data = $victims_model->handleGetVictimById($victim_id);
        $victim_data = $data['Victim'];
        $prosecutor_data = $data['Prosecutor'];
        $response_data = [
            'Victim' => $victim_data,
            'Prosecutor' => $prosecutor_data
        ];
        return $this->prepareOkResponse($response, $response_data);
    }
}
