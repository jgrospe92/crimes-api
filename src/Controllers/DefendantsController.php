<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Fig\Http\Message\StatusCodeInterface;

// HTTP exceptions
use Exception;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Vanier\Api\exceptions\HttpConflict;
use Vanier\Api\Exceptions\HttpUnprocessableContent;

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

        return $this->preparedResponse($response, $data);
    }

    /**
     * Summary of handlePostDefendants
     * @param Request $request
     * @param Response $response
     * @throws HttpBadRequestException
     * @return Response
     */
    public function handlePostDefendants(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        // Check if the JSON body is empty
        if (!$data)
        { 
            throw new HttpBadRequestException($request, 'No data to be added.');
        }

        // Validation loop
        foreach ($data as $defendant)
        {
            // Check if $data is empty of objects
            if (!$defendant)
            {
                throw new HttpBadRequestException($request, 'No data to be added.');
            }

            if (!ValidateHelper::validatePostMethods($defendant, "defendant")) 
            {
                throw new HttpBadRequestException($request, 'Either you are missing needed columns, or you are passing in invalid values. Refer to documentation.');
            }
        }

        $names = "";
        $arr_size = count($data);
        $i = 0;

        // Creation loop
        foreach ($data as $defendant) 
        {
            $this->defendant_model->postDefendant($defendant);
            if (++$i === $arr_size) 
            {
                $names .= "and " . $defendant["first_name"] . " " . $defendant["last_name"] . " ";
            } 
            else 
            {
                $names .= $defendant["first_name"] . " " . $defendant["last_name"] . ", ";
            }
        }

        // Prepare response message
        $message = ["message" => "Defendants " . $names . "have been created."];
        return $this->preparedResponse($response, $message, StatusCodeInterface::STATUS_CREATED);
    }

    /**
     * Summary of handlePutDefendant
     * @param Request $request
     * @param Response $response
     * @throws HttpBadRequestException
     * @throws HttpNotFoundException
     * @return Response
     */
    public function handlePutDefendants(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        // Check if the JSON body is empty
        if (!$data)
        { 
            throw new HttpBadRequestException($request, 'No data to be added.');
        }

        // Validation loop
        foreach ($data as $defendant)
        {
            // Check if $data is empty
            if (!$defendant)
            {
                throw new HttpBadRequestException($request, 'No data to be added.');
            }

            if (!ValidateHelper::validatePutMethods($defendant, 'defendant'))
            {
                throw new HttpBadRequestException($request, 'Either you are missing needed columns, or you are passing in invalid values. Refer to documentation.');
            }

            if (!$this->defendant_model->checkIfResourceExists('defendants', ['defendant_id' => $defendant['defendant_id']]))
            {
                throw new HttpNotFoundException($request, 'Either the requested defendant does not exist, or it has been deleted.');
            }
        }

        $names = "";
        $arr_size = count($data);
        $i = 0;

        // Update loop
        foreach ($data as $defendant)
        {
            $this->defendant_model->putDefendant($defendant);
            if (++$i === $arr_size) 
            {
                $names .= "and " . $defendant["defendant_id"] . " ";
            } 
            else 
            {
                $names .= $defendant["defendant_id"] . ", ";
            }
        }

        // Prepare response message
        $message = ["message" => "Defendants " . $names . "have been updated."];
        return $this->preparedResponse($response, $message);
    }

    public function handleDeleteDefendants(Request $request, Response $response)
    {
        $data = $request->getParsedBody()['defendant_id'];

        // Check if the JSON body is empty
        if (!$data || !is_array($data))
        {
            throw new HttpBadRequestException($request, "No data to be deleted.");
        }

        // Validate if each ID is valid and unique
        if (!ValidateHelper::arrayIsUnique($data)) 
        {
            throw new HttpBadRequestException($request, "One or more IDs are duplicated.");
        }

        $names = "";
        $arr_size = count($data);
        $i = 0;

        // Validation loop
        foreach ($data as $defendant_id)
        {
            if (!$this->defendant_model->checkIfResourceExists('defendants', ['defendant_id' => $defendant_id]))
            {
                throw new HttpNotFoundException($request, 'One or more prosecutors do not exist, or they have been deleted.');
            }
        }

        $names = "";
        $arr_size = count($data);
        $i = 0;

        // Deletion loop
        foreach ($data as $defendant_id)
        {
            $this->defendant_model->deleteDefendant($defendant_id);
            if (++$i === $arr_size) 
            {
                $names .= "and " . $defendant_id . " ";
            } 
            else 
            {
                $names .= $defendant_id . ", ";
            }
        }

        // Prepare response message
        $message = ["message" => "Defendants " . $names . "have been deleted."];
        return $this->preparedResponse($response, $message);
    }
}
