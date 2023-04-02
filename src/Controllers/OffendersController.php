<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// HTTP exceptions
use Exception;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Vanier\Api\exceptions\HttpBadRequest;
use Vanier\Api\exceptions\HttpUnprocessableContent;

// Helpers
use Vanier\Api\Helpers\ValidateHelper;

// Models
use Vanier\Api\Models\OffendersModel;

/**
 * Summary of OffendersController
 */
class OffendersController extends BaseController
{
    private $offenders_model;
    private $filter_params = 
    [
        'id',
        'first-name',
        'last-name',
        'age',
        'marital-status',
        'date-min',
        'date-max',
        'time-min',
        'time-max',
        'page',
        'pageSize',
        'sort'
    ];

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->offenders_model = new OffendersModel();
    }

    /**
     * Summary of handleGetOffenderById
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpNotFoundException
     * @return Response
     */
    public function handleGetOffenderById(Request $request, Response $response, array $uri_args)
    {
        $offender_id = $uri_args['offender_id'];
        $filters = $request->getQueryParams();

        // Check if ID is numeric
        if (!ValidateHelper::validateId(['id' => $offender_id])) 
        {
            throw new HttpBadRequestException($request, "Enter a valid ID");
        }

        // Check if any params are present
        if ($filters)
        {
            throw new HttpUnprocessableContent($request, "Resource does not support filtering or pagination");
        }

        $data = $this->offenders_model->getOffenderById($offender_id);
        if (!$data) { throw new HttpNotFoundException($request); }

        return $this->prepareOkResponse($response, $data);
    }

    /**
     * Summary of handleGetAllOffenders
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handleGetAllOffenders(Request $request, Response $response)
    {   
        // Constant values
        define('DEFAULT_PAGE', 1);
        define("DEFAULT_PAGE_SIZE", 10);

        $filters = $request->getQueryParams();

        // Validate filters
        if($filters)
        {
            foreach ($filters as $key => $value) 
            {
                if(!ValidateHelper::validateParams($key, $this->filter_params))
                {
                    throw new HttpUnprocessableContent($request, 'Invalid query parameter: ' . ' {' . $key . '}');                    
                }
                elseif (strlen($value) == 0) 
                {
                    throw new HttpUnprocessableContent($request, 'Provide query value for : ' . '{' . $key . '}');
                }
            }
        }

        // Validate params that require specific values
        if (isset($filters['id']))
        {
            if (!ValidateHelper::validateNumericInput(['offender_id' => $filters['id']])) 
            {
                throw new HttpBadRequestException($request, "Expected numeric value, received alpha");
            }
        }

        if (isset($filters['age']))
        {
            if (!ValidateHelper::validateNumericInput(['age' => $filters['age']])) 
            {
                throw new HttpBadRequestException($request, "Expected numeric value, received alpha");
            }
        }

        // Date validations
        if (isset($filters['date-min']) && isset($filters['date-max']))
        {
            if (!ValidateHelper::validateDateInput(['from_rentalDate' => $filters['date-min'], 'to_rentalDate' => $filters['date-max']]))
            {
                throw new HttpBadRequestException($request, "Bad date format. Make sure it is in this format: YYYY-MM-DD");
            }
        }
        elseif (isset($filters['date-min']))
        {
            if (!ValidateHelper::validateDateInput(['from_rentalDate' => $filters['date-min'], 'to_rentalDate' => '9999-12-31']))
            {
                throw new HttpBadRequestException($request, "Bad date format. Make sure it is in this format: YYYY-MM-DD");
            }
        }
        elseif (isset($filters['date-max']))
        {
            if (!ValidateHelper::validateDateInput(['from_rentalDate' => '1901-12-31', 'to_rentalDate' => $filters['date-max']]))
            {
                throw new HttpBadRequestException($request, "Bad date format. Make sure it is in this format: YYYY-MM-DD");
            }
        }

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

        $this->offenders_model->setPaginationOptions($page, $pageSize);

        // Catch any DB exceptions
        try { $data = $this->offenders_model->getAllOffenders($filters); } 
        catch (Exception $e) { throw new HttpBadRequestException($request); }

        // Throw a HttpNotFound error if data is empty
        if (!$data['offenders']) { throw new HttpNotFoundException($request); }

        return $this->prepareOkResponse($response, $data);
    }

    /**
     * Summary of handleGetDefendantOfOffender
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpNotFoundException
     * @return Response
     */
    public function handleGetDefendantOfOffender(Request $request, Response $response, array $uri_args) 
    {
        $offender_id = $uri_args['offender_id'];

        $filters = $request->getQueryParams();

        if (!ValidateHelper::validateId(['id' => $offender_id])) 
        {
            throw new HttpBadRequestException($request, "Enter a valid ID");
        }

        if ($filters)
        {
            throw new HttpUnprocessableContent($request, "Resource does not support filtering or pagination");
        }

        $data = $this->offenders_model->getDefendantOfOffender($offender_id);
        if (!$data) { throw new HttpNotFoundException($request); }

        return $this->prepareOkResponse($response, $data);
    }

    /**
     * Summary of handleGetCaseOfOffender
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpNotFoundException
     * @return Response
     */
    public function handleGetCaseOfOffender(Request $request, Response $response, array $uri_args) 
    {
        $offender_id = $uri_args['offender_id'];
        $filters = $request->getQueryParams();

        if (!ValidateHelper::validateId(['id' => $offender_id])) 
        {
            throw new HttpBadRequestException($request, "Enter a valid ID");
        }

        if ($filters)
        {
            throw new HttpUnprocessableContent($request, "Resource does not support filtering or pagination");
        }

        $data = $this->offenders_model->getCaseOfOffender($offender_id);
        if (!$data) { throw new HttpNotFoundException($request); }

        return $this->prepareOkResponse($response, $data);
    }
}
