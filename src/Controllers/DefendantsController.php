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
use Vanier\Api\Models\DefendantsModel;

/**
 * Summary of DefendantsController
 */
class DefendantsController extends BaseController
{
    private $defendant_model;
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
        $filters = $request->getQueryParams();
        
        // Check if ID is numeric
        if (!ValidateHelper::validateId(['id' => $defendant_id])) 
        {
            throw new HttpBadRequestException($request, "Enter a valid ID");
        }

        // Check if any params are present
        if ($filters)
        {
            throw new HttpUnprocessableContent($request, "Resource does not support filtering or pagination");
        }
        
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
            if (!ValidateHelper::validateNumericInput(['defendant_id' => $filters['id']])) 
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

        $pageParams = 
        [
            'page'          => $page, 
            'pageSize'      => $pageSize, 
            'pageMin'       => 1, 
            'pageSizeMin'   => 5, 
            'pageSizeMax'   => 10
        ];

        // Check if the page is within range 
        if (!ValidateHelper::validatePagingParams($pageParams)) 
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

    public function handlePostDefendants(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        foreach ($data as $key => $defendant)
        {
            $this->defendant_model->postDefendant($defendant);
        }

        return $response->withStatus(StatusCodeInterface::STATUS_CREATED);
    }

    public function handlePutDefendant(Request $request, Response $response, array $uri_args)
    {
        $defendant_id = $uri_args['defendant_id'];
        $data = $request->getParsedBody();
        var_dump($data);
        return $this->defendant_model->putDefendant($defendant_id, $data);
    }

    public function handleDeleteDefendant(Request $request, Response $response)
    {
        
    }
}
