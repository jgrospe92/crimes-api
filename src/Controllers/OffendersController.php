<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// HTTP exceptions
use Slim\Exception\HttpNotFoundException;

// Helpers
use Vanier\Api\Helpers\ValidateHelper;

// Models
use Vanier\Api\Models\OffendersModel;

/**
 * Summary of OffendersController
 */
class OffendersController extends BaseController
{
    private $offenders_model;

    public function __construct()
    {
        $this->offenders_model = new OffendersModel();
    }

    /**
     * Summary of handleGetOffenderById
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpNotFoundException
     * @return Response
     */
    public function handleGetOffenderById(Request $request, Response $response, array $uri_args)
    {
        $offender_id = $uri_args['offender_id'];
        $data = $this->offenders_model->getOffenderById($offender_id);

        if (!$data) { throw new HttpNotFoundException($request); }

        return $this->prepareOkResponse($response, $data);
    }

    /**
     * Summary of handleGetAllOffenders
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handleGetAllOffenders(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $data = $this->offenders_model->getAllOffenders($filters);
        return $this->prepareOkResponse($response, $data);
    }

    public function handleGetDefendantOfOffender(Request $request, Response $response, array $uri_args) 
    {
        $offender_id = $uri_args['offender_id'];
        $data = $this->offenders_model->getDefendantOfOffender($offender_id);

        if (!$data) { throw new HttpNotFoundException($request); }

        return $this->prepareOkResponse($response, $data);
    }

    public function handleGetCaseOfOffender(Request $request, Response $response, array $uri_args) 
    {
        $offender_id = $uri_args['offender_id'];
        $data = $this->offenders_model->getCaseOfOffender($offender_id);

        if (!$data) { throw new HttpNotFoundException($request); }

        return $this->prepareOkResponse($response, $data);
    }
}
