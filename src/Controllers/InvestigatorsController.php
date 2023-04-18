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
use Vanier\Api\models\InvestigatorsModel;

/**
 * Summary of InvestigatorsController
 */
class InvestigatorsController extends BaseController
{

    private $investigator_model = null;

    private array $FILTER_PARAMS = [
        'badge_number', 'page', 'pageSize',
        'first_name', 'sort_by',
        'last_name',
        'rank',
    ];


    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->investigator_model = new InvestigatorsModel();
    }

    /**
     * Summary of handleInvestigators
     * @param Request $request
     * @param Response $response
     * @throws HttpUnprocessableContent
     * @throws HttpBadRequest
     * @throws HttpNotFound
     * @return Response
     */
    public function handleInvestigators(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();

        // constant values
        define('DEFAULT_PAGE', 1);
        define("DEFAULT_PAGE_SIZE", 10);

        // validate filters
        if ($filters) {
            foreach ($filters as $key => $value) {
                if (!ValidateHelper::validateParams($key, $this->FILTER_PARAMS)) {
                    throw new HttpUnprocessableContent($request, 'Invalid query parameter: ' . ' {' . $key . '}');
                } elseif (strlen($value) == 0) {
                    throw new HttpUnprocessableContent($request, 'Please provide query value for : ' . '{' . $key . '}');
                }
            }
        }

        if (isset($filters['badge_number'])) {

            if (!ValidateHelper::validateNumericInput(['badge_number' => $filters['badge_number']])) {
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

        $this->investigator_model->setPaginationOptions($page, $pageSize);

        // catch any DB exceptions
        try {
            $data = $this->investigator_model->getAll($filters);
        } catch (Exception $e) {
            throw new HttpBadRequest($request, "Invalid request Syntax, please refer to the documentation");
        }
        // throw a HttpNotFound error if data is empty
        if (!$data['investigators']) {
            throw new HttpNotFound($request, 'please check you parameter or consult the documentation');
        }

        // return parsed data
        return $this->preparedResponse($response, $data, StatusCodeInterface::STATUS_OK);
    }

    /**
     * Summary of handleInvestigatorsById
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpBadRequest
     * @throws HttpNotFound
     * @return Response
     */
    public function handleInvestigatorsById(Request $request, Response $response, array $uri_args)
    {
        $investigator_id = $uri_args['investigator_id'];
        if (!ValidateHelper::validateId(['id' => $investigator_id])) {
            throw new HttpBadRequest($request, "please enter a valid id");
        }
        $filters = $request->getQueryParams();
        if ($filters) {
            throw new HttpUnprocessableContent($request, "Resource does not support filtering/paginate");
        }
        $whereClause = ['investigator_id' => $investigator_id];
        $data['Investigator'] = $this->investigator_model->getInvestigatorById("investigators", $whereClause);

        if (!$data['Investigator']) {
            throw new HttpNotFound($request, "please check your query parameter or consult the documentation");
        }

        return $this->preparedResponse($response, $data);
    }

    /**
     * Summary of handlePostInvestigators
     * @param Request $request
     * @param Response $response
     * @throws HttpConflict
     * @return Response
     */
    public function handlePostInvestigators(Request $request, Response $response)
    {
        // Retrieve data
        $data = $request->getParsedBody();
        // check if body is empty, throw an exception otherwise
        if (!isset($data)) {
            throw new HttpConflict($request, "Please provide required data");
        }

        foreach ($data as $investigator) {
            if (!ValidateHelper::validatePostMethods($investigator, "investigator")) {
                $exception = new HttpConflict($request);
                $payload['statusCode'] = $exception->getCode();
                $payload['error']['description'] = $exception->getDescription();
                $payload['error']['message'] = $exception->getMessage();
                $payload['reason'] = $investigator;

                return $this->prepareErrorResponse($response, $payload, StatusCodeInterface::STATUS_CONFLICT);
            }
            $this->investigator_model->createInvestigator($investigator);
        }

        return $this->preparedResponse($response, $data, StatusCodeInterface::STATUS_CREATED);
    }

    public function handlePutInvestigators(Request $request, Response $response)
    {   
        // retrieve data
        $data = $request->getParsedBody();
        // check if body is empty, throw an exception otherwise
        if (!isset($data)) {
            throw new HttpConflict($request, "Please provide required data");
        }
        // validate data
        foreach($data as $investigator)
        {
            if (!ValidateHelper::validatePutMethods($investigator, 'investigator'))
            {
                $exception = new HttpConflict($request);
                return $this->parsedError($response, $investigator,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }

            // validate if the investigator_id exists
            if (!$this->investigator_model->checkIfResourceExists('investigators', ['investigator_id'=>$investigator['investigator_id']])){

                $exception = new HttpConflict($request);
                $exception->setDescription("investigator_id is invalid");
                return $this->parsedError($response, $investigator,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }
            // update the resource
            $this->investigator_model->updateInvestigator($investigator);

        }

        return $this->preparedResponse($response, $data, StatusCodeInterface::STATUS_CREATED);
    }
}
