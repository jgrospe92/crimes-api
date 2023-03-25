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

    /**
     * Handle the HTTP GET request to retrieve all victims with optional filters.
     *
     * @param Request $request 
     * @param Response $response 
     * @return Response 
     */
    public function handleGetAllVictims(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $victims_model = new VictimsModel();
        $data = $victims_model->handleGetAllVictims($filters);
        return $this->prepareOkResponse($response, $data);
    }

     /**
     * Handle the HTTP GET request to retrieve a victim by ID.
     * 
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @return Response
     */
    public function handleGetVictimById(Request $request, Response $response, array $uri_args) {     

        $victim_id = $uri_args ["victim_id"];

        // Instantiate the VictimsModel to retrieve the victim data.
        $victims_model = new VictimsModel();
        $data = $victims_model->handleGetVictimById($victim_id);

         // Extract the victim and prosecutor data from the retrieved data.
        $victim_data = $data['Victim'];
        $prosecutor_data = $data['Prosecutor'];

        // formatting the response
        $response_data = [
            'Victim' => $victim_data,
            'Prosecutor' => $prosecutor_data
        ];
        return $this->prepareOkResponse($response, $response_data);
    }
}
