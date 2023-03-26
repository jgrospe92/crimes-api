<?php

namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\OffendersModel;
use Vanier\Api\exceptions\HttpNotFound;

class OffendersController extends BaseController
{
    private $offenders_model;

    public function __construct()
    {
        $this->offenders_model = new OffendersModel();
    }

    public function handleGetOffenderById(Request $request, Response $response, array $uri_args)
    {
        $offender_id = $uri_args['offender_id'];
        $data['case'] = $this->offenders_model->getOffenderById($offender_id);

        if (!$data['case']) 
        {
            throw new HttpNotFound($request, "Check your query parameters or consult the documentation");
        }

        return $this->prepareOkResponse($response, $data);
    }

    public function handleGetAllOffenders(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $data = $this->offenders_model->getAllOffenders($filters);
        return $this->prepareOkResponse($response, $data);
    
    }
}
