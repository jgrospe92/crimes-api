<?php

namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\controllers\BaseController;
use Vanier\Api\Models\CrimeScenesModel;

// helpers
use Vanier\Api\Helpers\ValidateHelper;

// exceptions
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Vanier\Api\exceptions\HttpNotFound;
use Vanier\Api\exceptions\HttpBadRequest;
use Vanier\Api\exceptions\HttpUnprocessableContent;

class CrimeScenesController extends BaseController
{
    private $crime_scenes_model = null;

    public function __construct()
    {
        $this->crime_scenes_model = new CrimeScenesModel();
    }

    public function handleGetAllCrimeScenes(Request $request, Response $response) {
        // constant values
        define('DEFAULT_PAGE', 1);
        define("DEFAULT_PAGE_SIZE", 10);
        
        $filters = $request->getQueryParams();
        $this->validateFilters($request, $filters);

        $crime_scenes_model = new CrimeScenesModel();
        $data = $crime_scenes_model->handleGetAllCrimeScenes($filters);
        $page = $filters["page"] ?? DEFAULT_PAGE;
        $pageSize = $filters["pageSize"] ?? DEFAULT_PAGE_SIZE;

        // check if the params are numeric
        if (!ValidateHelper::validatePageNumbers($page, $pageSize)) {
            throw new HttpBadRequest($request, "Expected numeric");
        }
        $dataParams = ['page' => $page, 'pageSize' => $pageSize, 'pageMin' => 1, 'pageSizeMin' => 5, 'pageSizeMax' => 10];
        // check if page is within in range 
        if (!ValidateHelper::validatePagingParams($dataParams)) {
            throw new HttpUnprocessableContent($request, "Out of range, unable to process your request");
        }

        $this->crime_scenes_model->setPaginationOptions($page, $pageSize);

        // catch any DB exceptions
        try {
            $data = $this->crime_scenes_model->handleGetAllCrimeScenes($filters);
        } catch (Exception $e) {
            throw new HttpBadRequest($request, "Not the right syntax, consult the documentation");
        }
        // throw a HttpNotFound error if data is empty
        if (!$data['data']) {
            throw new HttpNotFound($request, 'Please check you parameter or consult the documentation');
        }
        
        return $this->prepareOkResponse($response, $data, StatusCodeInterface::STATUS_OK);
    }

    public function handleGetCrimeById(Request $request, Response $response, array $uri_args) {
        $crime_scene_id = $uri_args["crime_sceneID"];

        $crime_scenes_model = new CrimeScenesModel();

        $data = $crime_scenes_model->handleGetCrimeSceneById($crime_scene_id);

        // Http Exception
        if (empty($data)) {
            throw new HttpNotFound($request, "Please check your query parameter or consult the documentation.");
        }

        return $this->prepareOkResponse($response, $data);
    }

    /**
    * Validates the filters for retrieving all victims
    *
    * @param array $filters The filters to validate
    * @throws HttpBadRequest If any of the filters are invalid
     */
    private function validateFilters(Request $request, array $filters)
    {
        $valid_filters = ['street', 'city', 'crime_scene_id'];
        foreach ($filters as $filter => $value) {
            if (!in_array($filter, $valid_filters)) {
                throw new HttpBadRequest($request, "Invalid filter: $filter");
            }
            if ($filter == 'crime_scene_id' && !is_numeric($value)) {
                throw new HttpBadRequest($request, "Expected numeric for age");
            }
        }
    }
}
