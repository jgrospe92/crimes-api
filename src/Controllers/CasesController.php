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
    private array $filter_params = ['description', 'date','date_reported', 'misdemeanor',
    'crime_sceneID', 'investigator_id', 'court_id', 'date_from', 'date_to', 'page', 'pageSize'];


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
     */
    public function handleGetCaseById(Request $request, Response $response, array $uri_args)
    {
        $case_id = $uri_args['case_id'];
        $whereClause = ['case_id' => $case_id];
        $data['Case'] = $this->case_model->getCaseById($this->CASES_TABLE, $whereClause);
        return $this->preparedResponse($response, $data);

    }

    public function handleGetCases(Request $request, Response $response)
    {
        // constant values
        define('DEFAULT_PAGE', 1);
        define("DEFAULT_PAGE_SIZE", 10);

        // filter by title
        $filters = $request->getQueryParams();

        // validate filters
        if ($filters){
            foreach($filters as $key => $value)
            {
                if (!ValidateHelper::validateParams($key, $this->filter_params)){
                    throw new HttpUnprocessableContent($request, 'Invalid query parameter: ' . ' {' . $key . '}');
                } elseif (empty($value))
                {
                    throw new HttpUnprocessableContent($request, 'Please provide query value for : ' . '{' . $key . '}');
                }
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
        $dataParams = ['page' => $page, 'pageSize' => $pageSize, 'pageMin' => 1,'pageSizeMin' => 5, 'pageSizeMax' => 10];
        // check if page is within in range else throw unprocessable content
        if (!ValidateHelper::validatePagingParams($dataParams)) {
            throw new HttpUnprocessableContent($request, "Out of range, unable to process your request, please consult the manual");
        }

        $this->case_model->setPaginationOptions($page, $pageSize);

        // catch any DB exceptions
        try {
            $data = $this->case_model->getAll($filters);
        } catch (Exception $e) {
            throw new HttpBadRequest($request, "Invalid request Syntax, please refer to the manual");
        }
        // throw a HttpNotFound error if data is empty
        if (!$data['data'])
        {
            throw new HttpNotFound($request, 'please check you parameter or consult the documentation');
        }

        // return parsed data
        return $this->preparedResponse($response, $data, StatusCodeInterface::STATUS_OK);




    }
}