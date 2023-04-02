<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
// exceptions
use Exception;
use Fig\Http\Message\StatusCodeInterface;
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
        'crime_sceneID', 'investigator_id', 'court_id', 'date_from', 'date_to', 'sort_by', 'page', 'pageSize'
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
        if ($filters)
        {
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

        if (isset($filters['misdemeanor'])){

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
}
