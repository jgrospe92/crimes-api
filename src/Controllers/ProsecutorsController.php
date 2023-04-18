<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Fig\Http\Message\StatusCodeInterface;

// HTTP exceptions
use Exception;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
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
    private $filter_params = 
    [
        'id',
        'first-name',
        'last-name',
        'age',
        'specialization',
        'page',
        'pageSize',
        'sort'
    ];

    private $column_names = ['first_name', 'last_name', 'age', 'specialization'];

    /**
     * Summary of __construct
     */
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
        $filters = $request->getQueryParams();

        // Check if ID is numeric
        if (!ValidateHelper::validateId(['id' => $prosecutor_id])) 
        {
            throw new HttpBadRequestException($request, "Enter a valid ID");
        }

        // Check if any params are present
        if ($filters)
        {
            throw new HttpUnprocessableContent($request, "Resource does not support filtering or pagination");
        }

        $data = $this->prosecutor_model->getProsecutorById($prosecutor_id);
        if (!$data) { throw new HttpNotFoundException($request); }
        
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
            if (!ValidateHelper::validateNumericInput(['prosecutor_id' => $filters['id']])) 
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

        $this->prosecutor_model->setPaginationOptions($page, $pageSize);

        // Catch any DB exceptions
        try { $data = $this->prosecutor_model->getAllProsecutors($filters); } 
        catch (Exception $e) { throw new HttpBadRequestException($request); }

        // Throw a HttpNotFound error if data is empty
        if (!$data['prosecutors']) { throw new HttpNotFoundException($request); }

        return $this->prepareOkResponse($response, $data);
    }

    public function handlePostProsecutors(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        // Check if $data is empty
        if (!$data) 
        { 
            throw new HttpBadRequestException($request, 'No columns to be added into the database.');
        }

        
        foreach ($data as $prosecutor)
        {
            // Check if $prosecutors are empty in $data
            if (!$prosecutor) 
            { 
                throw new HttpBadRequestException($request, 'One or more objects have no columns to be added into the database.'); 
            }

            // Check for missing columns
            $missing_columns = "";
            foreach ($this->column_names as $column)
            {
                if (!isset($prosecutor[$column])) { $missing_columns .= $column . ", "; }
            }

            if (!empty($missing_columns)) 
            {
                throw new HttpBadRequestException($request, 'Missing columns: ' . $missing_columns); 
            }

            // Check for missing values in the columns
            $missing_values = "";
            foreach ($prosecutor as $key => $column)
            {
                if (strlen($column) == 0) { $missing_values .= $key . ", "; }
                
            }

            if (!empty($missing_values))
            {
                throw new HttpBadRequestException($request, 'Missing values for: ' . $missing_values);
            }
        }

        foreach($data as $prosecutor)
        {
            $this->prosecutor_model->postProsecutor($prosecutor);
        }

        return $response->withStatus(StatusCodeInterface::STATUS_CREATED);
    }

    public function handlePutProsecutor(Request $request, Response $response, array $uri_args)
    {
        $prosecutor_id = $uri_args['prosecutor_id'];
        $data = $request->getParsedBody();
        return $this->prosecutor_model->putProsecutor($prosecutor_id, $data);
    }

    public function handleDeleteProsecutor(Request $request, Response $response)
    {
        
    }
}
