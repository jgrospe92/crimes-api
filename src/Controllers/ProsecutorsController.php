<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\ProsecutorsModel;

class ProsecutorsController extends BaseController
{
    private $prosecutor_model;

    public function __construct()
    {
        $this->prosecutor_model = new ProsecutorsModel();
    }

    public function handleGetProsecutorById(Request $request, Response $response, array $uri_args)
    {
        $prosecutor_id = $uri_args['prosecutor_id'];
        $data = $this->prosecutor_model->getProsecutorById($prosecutor_id);
        return $this->prepareOkResponse($response, $data);
    }

    public function handleGetAllProsecutors(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $data = $this->prosecutor_model->getAllProsecutors($filters);
        return $this->prepareOkResponse($response, $data);
    }
}
