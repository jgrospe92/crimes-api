<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// HTTP exceptions
use Slim\Exception\HttpNotFoundException;

// Helpers
use Vanier\Api\Helpers\ValidateHelper;

// Models
use Vanier\Api\Models\DefendantsModel;

/**
 * Summary of DefendantsController
 */
class DefendantsController extends BaseController
{
    private $defendant_model;

    public function __construct()
    {
        $this->defendant_model = new DefendantsModel();
    }

    /**
     * Summary of handleGetDefendantById
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @return Response
     */
    public function handleGetDefendantById(Request $request, Response $response, array $uri_args)
    {
        $defendant_id = $uri_args['defendant_id'];
        $data = $this->defendant_model->getDefendantById($defendant_id);

        if (!$data) { throw new HttpNotFoundException($request); }

        return $this->prepareOkResponse($response, $data);
    }

    /**
     * Summary of handleGetAllDefendants
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handleGetAllDefendants(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $data = $this->defendant_model->getAllDefendants($filters);
        return $this->prepareOkResponse($response, $data);
    }
}
