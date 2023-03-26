<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// HTTP exceptions
use Fig\Http\Message\StatusCodeInterface;
use Vanier\Api\exceptions\HttpNotFound;
use Vanier\Api\exceptions\HttpBadRequest;
use Vanier\Api\exceptions\HttpUnprocessableContent;

// Helpers
use Vanier\Api\Helpers\ValidateHelper;

// Models
use Vanier\Api\Models\ProsecutorsModel;

/**
 * Summary of ProsecutorsController
 */
class ProsecutorsController extends BaseController
{
    private $prosecutor_model;

    public function __construct()
    {
        $this->prosecutor_model = new ProsecutorsModel();
    }

    /**
     * Summary of handleGetProsecutorById
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @return Response
     */
    public function handleGetProsecutorById(Request $request, Response $response, array $uri_args)
    {
        $prosecutor_id = $uri_args['prosecutor_id'];
        $data = $this->prosecutor_model->getProsecutorById($prosecutor_id);
        return $this->prepareOkResponse($response, $data);
    }

    /**
     * Summary of handleGetAllProsecutors
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handleGetAllProsecutors(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $data = $this->prosecutor_model->getAllProsecutors($filters);
        return $this->prepareOkResponse($response, $data);
    }
}
