<?php
namespace Vanier\Api\Controllers;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\controllers\BaseController;
use Vanier\Api\exceptions\HttpBadRequest;
use Vanier\Api\exceptions\HttpNotFound;
use Vanier\Api\exceptions\HttpUnprocessableContent;
use Vanier\Api\Helpers\ValidateHelper;
use Vanier\Api\Models\CourtAddressesModel;

class CourtAddressesController extends BaseController
{
    private $court_addresses_model = null;
    private array $filter_params = ['address_id', 'city', 'street', 'postal_code'];

    public function __construct()
    {
        $this->court_addresses_model = new CourtAddressesModel();
    }
    
    public function handleGetAllAddresses(Request $request, Response $response)
    {
        //echo "hi"; exit;
        $filters = $request->getQueryParams();
        $court_addresses_model = new CourtAddressesModel();

        // validation for filters
        if($filters){
            foreach ($filters as $key => $value) {
                if(!ValidateHelper::validateParams($key, $this->filter_params)){
                    throw new HttpUnprocessableContent($request, 'Invalid query Parameter: ' . ' {' . $key . '}');                    
                }
                elseif (strlen($value) == 0) {
                    throw new HttpUnprocessableContent($request, 'Please provide query value for : ' . '{' . $key . '}');
                }
            }
        }
        if (isset($filters['address_id'])){

            if (!ValidateHelper::validateNumericInput(['verdict_id' => $filters['verdict_id']])) {
                throw new HttpBadRequest($request, "expected numeric but received alpha");
            }
        }

        $data = $court_addresses_model->handleGetAllAddresses($filters);
        return $this->prepareOkResponse($response,$data);
    }

    public function handleGetAddressById(Request $request, Response $response, array $args)
    {
        $filters = $request->getQueryParams();
        $court_addresses_model = new CourtAddressesModel();
        $address_id = $args["address_id"];
        $data = $court_addresses_model->handleGetAddressById($address_id);

        if(!$data){
            throw new HttpNotFound($request, "please check your query parameter or consult the documentation");
        }

        return $this->prepareOkResponse($response,$data);
    }





}
