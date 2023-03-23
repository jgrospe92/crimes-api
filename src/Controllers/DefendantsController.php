<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\DefendantsModel;

class DefendantsController extends BaseController
{
    private $defendant_model;

    public function __construct()
    {
        $this->defendant_model = new DefendantsModel();
    }

    public function handleGetDefendantById(Request $request, Response $response, array $uri_args)
    {
        $defendant_id = $uri_args['defendant_id'];
        $data = $this->defendant_model->getDefendantById($defendant_id);
        return $this->prepareOkResponse($response, $data);
    }

    public function handleGetAllDefendants(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $data = $this->defendant_model->getAllDefendants($filters);
        return $this->prepareOkResponse($response, $data);
    }
}
