<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// HTTP exceptions
use Exception;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Vanier\Api\exceptions\HttpUnprocessableContent;

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

    /**
     * Summary of __construct
     */
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
        // Constant values
        define('DEFAULT_PAGE', 1);
        define("DEFAULT_PAGE_SIZE", 10);

        $filters = $request->getQueryParams();

        // Define default page size if not specified
        $page = $filters["page"] ?? DEFAULT_PAGE;
        $pageSize = $filters["pageSize"] ?? DEFAULT_PAGE_SIZE;

        // Check if the params are numeric
        if (!ValidateHelper::validatePageNumbers($page, $pageSize)) { throw new HttpBadRequestException($request); }

        $dataParams = 
        [
            'page'          => $page, 
            'pageSize'      => $pageSize, 
            'pageMin'       => 1, 
            'pageSizeMin'   => 5, 
            'pageSizeMax'   => 10
        ];

        // Check if the page is within range 
        if (!ValidateHelper::validatePagingParams($dataParams)) 
        {
            throw new HttpUnprocessableContent($request, "Out of range, unable to process your request");
        }

        $this->defendant_model->setPaginationOptions($page, $pageSize);

        // Catch any DB exceptions
        try { $data = $this->defendant_model->getAllDefendants($filters); } 
        catch (Exception $e) { throw new HttpBadRequestException($request); }

        // Throw a HttpNotFound error if data is empty
        if (!$data['defendants']) { throw new HttpNotFoundException($request); }

        return $this->prepareOkResponse($response, $data);
    }
}
