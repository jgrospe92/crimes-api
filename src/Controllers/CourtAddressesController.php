<?php

namespace Vanier\Api\Controllers;

use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\controllers\BaseController;
use Vanier\Api\exceptions\HttpBadRequest;
use Vanier\Api\exceptions\HttpConflict;
use Vanier\Api\exceptions\HttpNotFound;
use Vanier\Api\exceptions\HttpUnprocessableContent;
use Vanier\Api\Helpers\ValidateHelper;
use Vanier\Api\Models\CourtAddressesModel;

/**
 * Summary of CourtAddressesController
 */
class CourtAddressesController extends BaseController
{
    private $court_addresses_model = null;
    private array $filter_params = ['address_id', 'city', 'street', 'postal_code', 'building_num'];

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->court_addresses_model = new CourtAddressesModel();
    }

    /**
     * Summary of handleGetAllAddresses
     * @param Request $request
     * @param Response $response
     * @throws HttpUnprocessableContent
     * @throws HttpBadRequest
     * @return Response
     */
    public function handleGetAllAddresses(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $court_addresses_model = new CourtAddressesModel();

        // validation for filters
        if ($filters) {
            foreach ($filters as $key => $value) {
                if (!ValidateHelper::validateParams($key, $this->filter_params)) {
                    throw new HttpUnprocessableContent($request, 'Invalid query Parameter: ' . ' {' . $key . '}');
                } elseif (strlen($value) == 0) {
                    throw new HttpUnprocessableContent($request, 'Please provide query value for : ' . '{' . $key . '}');
                }
            }
        }
        if (isset($filters['address_id'])) {

            if (!ValidateHelper::validateNumericInput(['address_id' => $filters['address_id']])) {
                throw new HttpBadRequest($request, "expected numeric but received alpha");
            }
        }

        $data = $court_addresses_model->handleGetAllAddresses($filters);
        return $this->prepareOkResponse($response, $data);
    }

    /**
     * Summary of handleGetAddressById
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @throws HttpNotFound
     * @return Response
     */
    public function handleGetAddressById(Request $request, Response $response, array $args)
    {
      
        $court_addresses_model = new CourtAddressesModel();
        $address_id = $args["address_id"];
        if (!ValidateHelper::validateId(['id' => $address_id])) {
            throw new HttpBadRequest($request, "please enter a valid id");
        }
        $filters = $request->getQueryParams();
        if ($filters)
        {
            throw new HttpUnprocessableContent($request, "Resource does not support filtering or pagination");
        }
        return $response->withStatus(StatusCodeInterface::STATUS_CREATED);
    }

    public function handleCreateAddresses(Request $request, Response $response)
    {
        $address_data = $request->getParsedBody();
        // to check if body is correct
        if(!isset($address_data)){
            throw new HttpBadRequest($request, "the request body is invalid");
        }

        foreach ($address_data as $key => $addresses) {
            if(!ValidateHelper::validatePostMethods($addresses, 'address')){
                $exception = new HttpBadRequest($request);
                return $this->parsedError($response, $addresses, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }

            // to check if postal_code is well formatted
            if(!ValidateHelper::validatePostalCode($addresses['postal_code'])){
                $exception = new HttpBadRequest($request);
                $exception->setDescription("Postal_Code format is invalid");
                return $this->parsedError($response,$addresses,$exception,StatusCodeInterface::STATUS_BAD_REQUEST);
            }
        }
        try {
            $this->court_addresses_model->handleCreateAddresses($addresses);
        } catch (Exception $e) {
            throw new HttpConflict($request, "Remove address_id from your body");
        }

        return $this->prepareOkResponse($response, $address_data);
    }


    public function handleUpdateAddressById(Request $request, Response $response, array $args)
    {
        $address_data = $request->getParsedBody();
        // to check if body is correct
        if(!isset($address_data)){
            throw new HttpBadRequest($request,"the request body is invalid");
        }

        foreach ($address_data as $key => $addresses) {
            if(!ValidateHelper::validatePostMethods($addresses, 'address')){
                $exception = new HttpBadRequest($request);
                return $this->parsedError($response, $addresses, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }

            // to check if postal_code is well formatted
            if(!ValidateHelper::validatePostalCode($addresses['postal_code'])){
                $exception = new HttpBadRequest($request);
                $exception->setDescription("Postal_Code format is invalid");
                return $this->parsedError($response,$addresses,$exception,StatusCodeInterface::STATUS_BAD_REQUEST);
            }

            // to check if address_id exists
            if (!$this->court_addresses_model->checkIfResourceExists('court_addresses', ['address_id' => $addresses['address_id']])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("address_id is invalid");
                return $this->parsedError($response, $addresses,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }

            $address_id = $addresses["address_id"];
            unset($addresses["address_id"]);
            //var_dump($address);exit;
            $this->court_addresses_model->handleUpdateAddressById($addresses,$address_id);
        }

        if(!$response->withStatus(StatusCodeInterface::STATUS_CREATED)){
            throw new HttpBadRequest($request,"The data entered was improperly formatted");
        }
        else{
            return $this->prepareOkResponse($response,$address_data);
        }
    }

}
