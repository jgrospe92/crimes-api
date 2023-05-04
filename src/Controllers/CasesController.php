<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
// exceptions
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Vanier\Api\exceptions\HttpConflict;
use Vanier\Api\exceptions\HttpNotFound;
use Vanier\Api\exceptions\HttpBadRequest;
use Vanier\Api\exceptions\HttpUnprocessableContent;
// helpers
use Vanier\Api\Helpers\ValidateHelper;
// models
use Vanier\Api\models\CasesModel;

/**
 * Summary of CasesController
 */
class CasesController extends BaseController
{
    private $case_model = null;
    private string $CASES_TABLE = 'cases';
    private array $FILTER_PARAMS = [
        'description', 'misdemeanor', 'classification', 'name',
        'crime_sceneID', 'investigator_id', 'court_id', 'date_from', 'date_to', 'sort_by', 'page', 'pageSize', 'time_stamp'
    ];

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->case_model = new CasesModel();
    }


    /**
     * Summary of handleGetCaseById
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @return Response
     * ? none supported filtering
     */
    public function handleGetCaseById(Request $request, Response $response, array $uri_args)
    {
        $case_id = $uri_args['case_id'];
        if (!ValidateHelper::validateId(['id' => $case_id])) {
            throw new HttpBadRequest($request, "please enter a valid id");
        }
        $filters = $request->getQueryParams();
        if ($filters) {
            throw new HttpUnprocessableContent($request, "Resource does not support filtering or pagination");
        }
        $whereClause = ['case_id' => $case_id];
        $data['Case'] = $this->case_model->getCaseById($this->CASES_TABLE, $whereClause);

        if (!$data['Case']) {
            throw new HttpNotFound($request, "please check your query parameter or consult the documentation");
        }

        return $this->preparedResponse($response, $data);
    }

    /**
     * Summary of handleOffendersByCase
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpBadRequest
     * @throws HttpUnprocessableContent
     * @return Response
     */
    public function handleOffendersByCase(Request $request, Response $response, array $uri_args)
    {
        $case_id = $uri_args['case_id'];
        // filter by title 
        $filters = $request->getQueryParams();
        if (!ValidateHelper::validateId(['id' => $case_id])) {
            throw new HttpBadRequest($request, "please enter a valid id");
        }
        $whereClause = ['case_id' => $case_id];
        // validate filters
        if ($filters) {
            foreach ($filters as $key => $value) {
                if (!ValidateHelper::validateParams($key, $this->FILTER_PARAMS)) {
                    throw new HttpUnprocessableContent($request, 'Invalid query parameter: ' . ' {' . $key . '}');
                } elseif (strlen($value)  == 0) {
                    throw new HttpUnprocessableContent($request, 'Please provide query value for : ' . '{' . $key . '}');
                }
            }
        }
        try {

            $data['Case'] = $this->case_model->offendersByCase($this->CASES_TABLE, $whereClause);
        } catch (Exception $e) {

            throw new HttpBadRequest($request, "Invalid request Syntax, please refer to the documentation");
        }

        return $this->preparedResponse($response, $data);
    }
    /**
     * Summary of handleVictimsByCase
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpBadRequest
     * @throws HttpUnprocessableContent
     * @return Response
     */
    public function handleVictimsByCase(Request $request, Response $response, array $uri_args)
    {
        $case_id = $uri_args['case_id'];
        // filter by title 
        $filters = $request->getQueryParams();
        if (!ValidateHelper::validateId(['id' => $case_id])) {
            throw new HttpBadRequest($request, "please enter a valid id");
        }
        $whereClause = ['case_id' => $case_id];
        // validate filters
        if ($filters) {
            foreach ($filters as $key => $value) {
                if (!ValidateHelper::validateParams($key, $this->FILTER_PARAMS)) {
                    throw new HttpUnprocessableContent($request, 'Invalid query parameter: ' . ' {' . $key . '}');
                } elseif (strlen($value)  == 0) {
                    throw new HttpUnprocessableContent($request, 'Please provide query value for : ' . '{' . $key . '}');
                }
            }
        }
        try {

            $data['Case'] = $this->case_model->victimsByCase($this->CASES_TABLE, $whereClause);
        } catch (Exception $e) {

            throw new HttpBadRequest($request, "Invalid request Syntax, please refer to the documentation");
        }

        return $this->preparedResponse($response, $data);
    }

    /**
     * Summary of handleOffensesByCase
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpBadRequest
     * @throws HttpUnprocessableContent
     * @throws HttpNotFound
     * @return Response
     */
    public function handleOffensesByCase(Request $request, Response $response, array $uri_args)
    {
        $case_id = $uri_args['case_id'];
        // filter by title 
        $filters = $request->getQueryParams();
        if (!ValidateHelper::validateId(['id' => $case_id])) {
            throw new HttpBadRequest($request, "please enter a valid id");
        }
        $whereClause = ['case_id' => $case_id];
        // validate filters
        if ($filters) {
            foreach ($filters as $key => $value) {
                if (!ValidateHelper::validateParams($key, $this->FILTER_PARAMS)) {
                    throw new HttpUnprocessableContent($request, 'Invalid query parameter: ' . ' {' . $key . '}');
                } elseif (strlen($value)  == 0) {
                    throw new HttpUnprocessableContent($request, 'Please provide query value for : ' . '{' . $key . '}');
                }
            }
        }
        try {

            $data['Case'] = $this->case_model->offensesByCase($this->CASES_TABLE, $whereClause);
        } catch (Exception $e) {

            throw new HttpBadRequest($request, "Invalid request Syntax, please refer to the documentation");
        }

        if (!$data['Case']) {
            throw new HttpNotFound($request, "please check your query parameter or consult the documentation");
        }
        return $this->preparedResponse($response, $data);
    }

    /**
     * Summary of handleGetCases
     * @param Request $request
     * @param Response $response
     * @throws HttpUnprocessableContent
     * @throws HttpBadRequest
     * @throws HttpNotFound
     * @return Response
     * ?
     */
    public function handleGetCases(Request $request, Response $response)
    {
        // constant values
        define('DEFAULT_PAGE', 1);
        define("DEFAULT_PAGE_SIZE", 10);

        // filter by title
        $filters = $request->getQueryParams();

        // validate filters
        if ($filters) {
            foreach ($filters as $key => $value) {
                if (!ValidateHelper::validateParams($key, $this->FILTER_PARAMS)) {
                    throw new HttpUnprocessableContent($request, 'Invalid query parameter: ' . ' {' . $key . '}');
                } elseif (strlen($value)  == 0) {
                    throw new HttpUnprocessableContent($request, 'Please provide query value for : ' . '{' . $key . '}');
                }
            }
        }
        // validate date
        if (isset($filters['date_from'])) {
            $date_format = $filters['date_from'];
            if (!ValidateHelper::validateDateFormat($date_format)) {
                throw new HttpUnprocessableContent($request, 'Invalid date: ' . ' {' . $date_format . '}');
            }
        }
        if (isset($filters['date_to'])) {
            $date_format = $filters['date_to'];
            if (!ValidateHelper::validateDateFormat($date_format)) {
                throw new HttpUnprocessableContent($request, 'Invalid date: ' . ' {' . $date_format . '}');
            }
        }
        if (isset($filters['misdemeanor'])) {

            if (!ValidateHelper::validateNumericInput(['misdemeanor' => $filters['misdemeanor']])) {
                throw new HttpBadRequest($request, "expected numeric but received alpha");
            }
        }

        // verify if client added a page and pageSize params
        // if client didn't add a page and pageSize params, paginate using the default values
        $page = $filters["page"] ?? DEFAULT_PAGE;
        $pageSize = $filters["pageSize"] ?? DEFAULT_PAGE_SIZE;

        // check if the params is numeric, if not throw a bad request error
        if (!ValidateHelper::validatePageNumbers($page, $pageSize)) {
            throw new HttpBadRequest($request, "expected numeric but received alpha");
        }
        $dataParams = ['page' => $page, 'pageSize' => $pageSize, 'pageMin' => 1, 'pageSizeMin' => 5, 'pageSizeMax' => 10];
        // check if page is within in range else throw unprocessable content
        if (!ValidateHelper::validatePagingParams($dataParams)) {
            throw new HttpUnprocessableContent($request, "Out of range, unable to process your request, please consult the documentation");
        }

        $this->case_model->setPaginationOptions($page, $pageSize);

        // catch any DB exceptions
        try {
            $data = $this->case_model->getAll($filters);
        } catch (Exception $e) {
            throw new HttpBadRequest($request, "Invalid request Syntax, please refer to the documentation");
        }
        // throw a HttpNotFound error if data is empty
        if (!$data['cases']) {
            throw new HttpNotFound($request, 'please check you parameter or consult the documentation');
        }

        // return parsed data
        return $this->preparedResponse($response, $data, StatusCodeInterface::STATUS_OK);
    }

    // POST METHOD
    /**
     * Summary of handlePostCases
     * @param Request $request
     * @param Response $response
     * @throws HttpConflict
     * @return Response
     * TODO refactor
     */
    public function handlePostCases(Request $request, Response $response)
    {
        // Retrieve data
        $data = $request->getParsedBody();
        // check if body is empty, throw an exception otherwise
        if (!isset($data)) {
            throw new HttpConflict($request, "Please provide required data");
        }
        foreach ($data as $case) {
            // validate if the provided data is correct
            if (!ValidateHelper::validatePostMethods($case, "cases")) {
                $exception = new HttpConflict($request);
                return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }
            // checks if all Foreign keys exist
            if (!$this->case_model->checkIfResourceExists('crime_scenes', ['crime_sceneID' => $case['crime_sceneID']])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("Crime-sceneID is invalid");
                return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }

            if (!$this->case_model->checkIfResourceExists('investigators', ['investigator_id' => $case['investigator_id']])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("investigator_id is invalid");
                return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }
            if (!$this->case_model->checkIfResourceExists('courts', ['court_id' => $case['court_id']])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("court_id is invalid");
                return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }

            // Uri Relationships
            // validate to make sure keys are unique
            if (!ValidateHelper::arrayIsUnique($case['offense_id']) || !ValidateHelper::arrayIsUnique($case['victim_id']) || !ValidateHelper::arrayIsUnique($case['offender_id'])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("duplicate keys are not allowed");
                return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }
            foreach ($case['offense_id'] as $id) {
                if (!ValidateHelper::validateNumIsPositive($id) || !$this->case_model->checkIfResourceExists('offenses', ['offense_id' => $id])) {
                    $exception = new HttpConflict($request);
                    $exception->setDescription("offense_id is invalid, make sure it exists or not a negative number");
                    return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
                }
            }
            foreach ($case['victim_id'] as $id) {
                if (!ValidateHelper::validateNumIsPositive($id) || !$this->case_model->checkIfResourceExists('victims', ['victim_id' => $id])) {
                    $exception = new HttpConflict($request);
                    $exception->setDescription("victim_id is invalid, make sure it exists or not a negative number");
                    return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
                }
            }
            foreach ($case['offender_id'] as $id) {
                if (!ValidateHelper::validateNumIsPositive($id) || !$this->case_model->checkIfResourceExists('offenders', ['offender_id' => $id])) {
                    $exception = new HttpConflict($request);
                    $exception->setDescription("offender_id is invalid, make sure it exists or not a negative number");
                    return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
                }
            }

            try {
                $this->case_model->createCases($case);
            } catch (Exception $e) {
                throw new HttpConflict($request, "Remove case_id from your body");
            }
        }

        return $this->preparedResponse($response, $data, StatusCodeInterface::STATUS_CREATED);
    }

    /**
     * Summary of handlePutCases
     * @param Request $request
     * @param Response $response
     * @throws HttpConflict
     * @throws HttpBadRequest
     * @return Response
     * TODO refactor
     */
    public function handlePutCases(Request $request, Response $response)
    {
        // Retrieve body
        $data = $request->getParsedBody();
        // check if body is empty, throw an exception otherwise
        if (!isset($data)) {
            throw new HttpConflict($request, "Please provide required data");
        }

        foreach ($data as $case) {
            // validate if the provided data is correct
            if (!ValidateHelper::validatePutMethods($case, "cases")) {
                $exception = new HttpConflict($request);
                return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }
            // validate if the case_id exists
            if (!$this->case_model->checkIfResourceExists('cases', ['case_id' => $case['case_id']])) {

                $exception = new HttpConflict($request);
                $exception->setDescription("case_id is invalid");
                return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }
            // checks if all Foreign keys exist

            if (!$this->case_model->checkIfResourceExists('crime_scenes', ['crime_sceneID' => $case['crime_sceneID']])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("Crime-sceneID is invalid");
                return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }


            if (!$this->case_model->checkIfResourceExists('investigators', ['investigator_id' => $case['investigator_id']])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("investigator_id is invalid");
                return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }
            if (!$this->case_model->checkIfResourceExists('courts', ['court_id' => $case['court_id']])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("court_id is invalid");
                return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }
            // URi Relationships
            // validate to make sure keys are unique
            if (!ValidateHelper::arrayIsUnique($case['offense_id']) || !ValidateHelper::arrayIsUnique($case['victim_id']) || !ValidateHelper::arrayIsUnique($case['offender_id'])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("duplicate keys are not allowed");
                return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }
            foreach ($case['offense_id'] as $id) {
                if (!ValidateHelper::validateNumIsPositive($id) || !$this->case_model->checkIfResourceExists('offenses', ['offense_id' => $id])) {
                    $exception = new HttpConflict($request);
                    $exception->setDescription("offense_id is invalid, make sure it exists or not a negative number");
                    return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
                }
            }
            foreach ($case['victim_id'] as $id) {
                if (!ValidateHelper::validateNumIsPositive($id) || !$this->case_model->checkIfResourceExists('victims', ['victim_id' => $id])) {
                    $exception = new HttpConflict($request);
                    $exception->setDescription("victim_id is invalid, make sure it exists or not a negative number");
                    return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
                }
            }
            foreach ($case['offender_id'] as $id) {
                if (!ValidateHelper::validateNumIsPositive($id) || !$this->case_model->checkIfResourceExists('offenders', ['offender_id' => $id])) {
                    $exception = new HttpConflict($request);
                    $exception->setDescription("offender_id is invalid, make sure it exists or not a negative number");
                    return $this->parsedError($response, $case,  $exception, StatusCodeInterface::STATUS_CONFLICT);
                }
            }

            $this->case_model->updateCase($case);
        }

        return $this->preparedResponse($response, $data, StatusCodeInterface::STATUS_CREATED);
    }
}
