<?php

namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\controllers\BaseController;
use Vanier\Api\Models\JudgesModel;

// helpers
use Vanier\Api\Helpers\ValidateHelper;

// exceptions
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Vanier\Api\exceptions\HttpNotFound;
use Vanier\Api\exceptions\HttpBadRequest;
use Vanier\Api\exceptions\HttpUnprocessableContent;
use Vanier\Api\exceptions\HttpNotAcceptableException;
use Vanier\Api\exceptions\HttpConflict;

/**
 * Summary of JudgesController
 */
class JudgesController extends BaseController
{
    private $judges_model = null;

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->judges_model = new JudgesModel();
    }

    /**
     * Summary of handleGetAllJudges
     * @param Request $request
     * @param Response $response
     * @throws HttpBadRequest
     * @throws HttpUnprocessableContent
     * @throws HttpNotFound
     * @return Response
     */
    public function handleGetAllJudges(Request $request, Response $response) {
        // constant values
        define('DEFAULT_PAGE', 1);
        define("DEFAULT_PAGE_SIZE", 10);
        
        $filters = $request->getQueryParams();
        $this->validateFilters($request, $filters);

        $judges_model = new JudgesModel();
        $data = $judges_model->handleGetAllJudges($filters);
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

        $this->judges_model->setPaginationOptions($page, $pageSize);

        // catch any DB exceptions
        try {
            $data = $this->judges_model->handleGetAllJudges($filters);
        } catch (Exception $e) {
            throw new HttpBadRequest($request, "Not the right syntax, consult the documentation");
        }
        // throw a HttpNotFound error if data is empty
        if (!$data['judges']) {
            throw new HttpNotFound($request, 'Please check you parameter or consult the documentation');
        }
        
        return $this->prepareOkResponse($response, $data, StatusCodeInterface::STATUS_OK);
    }

    /**
     * Summary of handleGetJudgeById
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpNotFound
     * @return Response
     */
    public function handleGetJudgeById(Request $request, Response $response, array $uri_args) {
        $judge_id = $uri_args["judge_id"];
        if (!ValidateHelper::validateId(['id' => $judge_id])) {
            throw new HttpBadRequest($request, "please enter a valid id");
        }
        $filters = $request->getQueryParams();
        if ($filters)
        {
            throw new HttpUnprocessableContent($request, "Resource does not support filtering or pagination");
        }

        $judges_model = new JudgesModel();

        $data['judge'] = $judges_model->handleGetJudgeById($judge_id);

        // Http Exception
        if (!$data['judge']) {
            throw new HttpNotFound($request, "Please check your query parameter or consult the documentation.");
        }

        return $this->prepareOkResponse($response, $data);
    }

    /**
     * This function creates a judge
     *
     * @param Request $request
     * @param Response $response
     * @return mixed response
     */
    public function createJudge(Request $request, Response $response)
    {
        // Retrieve data
        $data = $request->getParsedBody();

        // check if body is empty or not an array, throw an exception otherwise
        if (empty($data) || !is_array($data)) {
            throw new HttpConflict($request, "Please provide required data");
        }

        // Validate the received data
        if (!ValidateHelper::validatePostMethods($data, "judge")) {
            $exception = new HttpConflict($request);
            $payload['statusCode'] = $exception->getCode();
            $payload['error']['description'] = $exception->getDescription();
            $payload['error']['message'] = $exception->getMessage();

            return $this->prepareErrorResponse($response, $payload, StatusCodeInterface::STATUS_CONFLICT);
        }

        // Create a new judge
        $newJudge = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'age' => $data['age']
        ];

        $this->judges_model->createJudge($newJudge);

        $reponseMessage = "You have successfully created a new judge.";
        $responseData = [
            'message' => $reponseMessage,
            'judge' => $newJudge
        ];
        

        return $this->preparedResponse($response, $responseData, StatusCodeInterface::STATUS_CREATED);
    }

    public function updateJudges(Request $request, Response $response)
    {
        // Retrieve data
        $data = $request->getParsedBody();

        // check if body is empty or not an array, throw an exception otherwise
        if (empty($data) || !is_array($data)) {
            throw new HttpConflict($request, "Please provide required data");
        }

        // Validate the received data for each judge
        foreach ($data['judges'] as $judge) {
            if (!ValidateHelper::validatePutMethods($judge, 'judge')) {
                $exception = new HttpConflict($request, "Something is not valid");
                $payload['statusCode'] = $exception->getCode();
                $payload['error']['description'] = $exception->getDescription();
                $payload['error']['message'] = $exception->getMessage();

                return $this->prepareErrorResponse($response, $payload, StatusCodeInterface::STATUS_CONFLICT);
            }
        }

        // Update the judges
        $judges = $data['judges'];
        $this->judges_model->updateJudges($judges);

        $reponseMessage = [
            "message" => "You have successfully updated the judges.",
            "judges" => $judges
        ];

        return $this->preparedResponse($response, $reponseMessage, StatusCodeInterface::STATUS_OK);
    }

    public function deleteJudges(Request $request, Response $response, array $args)
    {
        $judgeIds = $request->getParsedBody()['judge_id'];

        // Check if ids are provided
        if (empty($judgeIds) || !is_array($judgeIds)) {
            throw new HttpConflict($request, "Please provide an id");
        }

        // Validate if each ID is valid and unique
        if (!ValidateHelper::arrayIsUnique($judgeIds)) {
            throw new HttpConflict($request, "Id is not valid/unique");
        }

        // Check if each ID exists before deleting
        foreach ($judgeIds as $judgeId) {
            if (!$this->judges_model->judgeExists($judgeId)) {
                throw new HttpConflict($request, "Judge with id $judgeId does not exist");
            }
        }

        // Delete the judges
        $deletedCount = 0;
        foreach ($judgeIds as $judgeId) {
            $deletedCount++;
            $this->judges_model->deleteJudge($judgeId);
        }

        // Prepare response message
        $responseMessage = [
            "message" => "You have successfully deleted $deletedCount judge(s).",
        ];

        return $this->preparedResponse($response, $responseMessage, StatusCodeInterface::STATUS_OK);
    }

    /**
    * Validates the filters for retrieving all victims
    *
    * @param array $filters The filters to validate
    * @throws HttpBadRequest If any of the filters are invalid
     */
    private function validateFilters(Request $request, array $filters)
    {
        $valid_filters = ['last_name', 'age', 'judge_id', 'sort_by'];
        foreach ($filters as $filter => $value) {
            if (!in_array($filter, $valid_filters)) {
                throw new HttpBadRequest($request, "Invalid filter: $filter");
            }
            if ($filter == 'age' && !is_numeric($value)) {
                throw new HttpBadRequest($request, "Expected numeric for age");
            }
        }
    }
}
