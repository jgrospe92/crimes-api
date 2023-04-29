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
use Vanier\Api\exceptions\HttpConflict;

/**
 * Summary of CrimeScenesController
 */
class CrimeScenesController extends BaseController
{
    private $crime_scenes_model = null;

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->crime_scenes_model = new CrimeScenesModel();
    }

    /**
     * Summary of handleGetAllCrimeScenes
     * @param Request $request
     * @param Response $response
     * @throws HttpBadRequest
     * @throws HttpUnprocessableContent
     * @throws HttpNotFound
     * @return Response
     */
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
        if (!$data['crime_scenes']) {
            throw new HttpNotFound($request, 'Please check you parameter or consult the documentation');
        }
        
        return $this->prepareOkResponse($response, $data, StatusCodeInterface::STATUS_OK);
    }

    /**
     * Summary of handleGetCrimeById
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpNotFound
     * @return Response
     */
    public function handleGetCrimeById(Request $request, Response $response, array $uri_args) {
        $crime_scene_id = $uri_args["crime_sceneID"];

        $crime_scenes_model = new CrimeScenesModel();
        if (!ValidateHelper::validateId(['id' => $crime_scene_id])) {
            throw new HttpBadRequest($request, "please enter a valid id");
        }
        $filters = $request->getQueryParams();
        if ($filters)
        {
            throw new HttpUnprocessableContent($request, "Resource does not support filtering or pagination");
        }

        $data['crime scene'] = $crime_scenes_model->handleGetCrimeSceneById($crime_scene_id);

        // Http Exception
        if (empty($data)) {
            throw new HttpNotFound($request, "Please check your query parameter or consult the documentation.");
        }

        return $this->prepareOkResponse($response, $data);
    }

    /**
     * This function creates a new crime scene
     *
     * @param Request $request
     * @param Response $response
     * @return mixed response
     */
    public function createCrimeScene(Request $request, Response $response)
    {
        // Retrieve data
        $data = $request->getParsedBody();

        // check if body is empty or not an array, throw an exception otherwise
        if (empty($data) || !is_array($data)) {
            throw new HttpConflict($request, "Please provide required data");
        }

        // Validate the received data
        if (!ValidateHelper::validatePostMethods($data, "crime_scene")) {
            $exception = new HttpConflict($request, "Something is not valid");
            $payload['statusCode'] = $exception->getCode();
            $payload['error']['description'] = $exception->getDescription();
            $payload['error']['message'] = $exception->getMessage();

            return $this->prepareErrorResponse($response, $payload, StatusCodeInterface::STATUS_NOT_ACCEPTABLE);
        }

        // Create a new crime scene
        $newCrimeScene = [
            'province' => $data['province'],
            'city' => $data['city'],
            'street' => $data['street'],
            'building_number' => $data['building_number']
        ];

        $this->crime_scenes_model->createCrimeScene($newCrimeScene);

        $reponseMessage = "You have successfully created a new crime scene.";
        $responseData = [
            'message' => $reponseMessage,
            'crime_scene' => $newCrimeScene
        ];
        
        return $this->preparedResponse($response, $responseData, StatusCodeInterface::STATUS_CREATED);
    }

    public function updateCrimeScenes(Request $request, Response $response)
    {
        // Retrieve data
        $data = $request->getParsedBody();

        // check if body is empty or not an array, throw an exception otherwise
        if (empty($data) || !is_array($data)) {
            throw new HttpConflict($request, "Please provide required data");
        }

        // Validate the received data for each judge
        foreach ($data['crime_scenes'] as $crime_scene) {
            if (!ValidateHelper::validatePutMethods($crime_scene, 'crime_scene')) {
                $exception = new HttpConflict($request, "Something is not valid");
                $payload['statusCode'] = $exception->getCode();
                $payload['error']['description'] = $exception->getDescription();
                $payload['error']['message'] = $exception->getMessage();

                return $this->prepareErrorResponse($response, $payload, StatusCodeInterface::STATUS_CONFLICT);
            }
        }
        // Update the crime_scenes
        $crime_scenes = $data['crime_scenes'];
        $this->crime_scenes_model->updateCrime_Scenes($crime_scenes);

        $reponseMessage = [
            "message" => "You have successfully updated the crime_Scenes.",
            "crime_scenes" => $crime_scenes
        ];

        return $this->preparedResponse($response, $reponseMessage, StatusCodeInterface::STATUS_OK);
    }

    public function deleteCrimeScenes(Request $request, Response $response, array $args)
    {
        $crime_scene_ids = $request->getParsedBody()['crime_sceneID'];

        // Check if ids are provided
        if (empty($crime_scene_ids) || !is_array($crime_scene_ids)) {
            throw new HttpConflict($request, "Please provide an id");
        }

        // Validate if each ID is valid and unique
        if (!ValidateHelper::arrayIsUnique($crime_scene_ids)) {
            throw new HttpConflict($request, "Id is not valid/unique");
        }

        // Check if each ID exists before deleting
        foreach ($crime_scene_ids as $crime_scene_id) {
            if (!$this->crime_scenes_model->CrimeSceneExists($crime_scene_id)) {
                throw new HttpConflict($request, "CrimeScenes with id $crime_scene_id does not exist");
            }
        }

        // Delete the judges
        $deletedCount = 0;
        foreach ($crime_scene_ids as $crime_scene_id) {
            $deletedCount++;
            $this->crime_scenes_model->deleteJudge($crime_scene_id);
        }

        // Prepare response message
        $responseMessage = [
            "message" => "You have successfully deleted $deletedCount crime(s).",
        ];

        return $this->preparedResponse($response, $responseMessage, StatusCodeInterface::STATUS_OK);
    }


    /**
    *
    * @param array $filters
    * @throws HttpBadRequest
     */
    private function validateFilters(Request $request, array $filters)
    {
        $valid_filters = ['street', 'city', 'crime_scene_id', 'sort_by'];
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
