<?php
namespace Vanier\Api\Controllers;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\controllers\BaseController;
use Vanier\Api\Models\CourtAddressesModel;

class CourtAddressesController extends BaseController
{
    private $court_addresses_model = null;

    public function __construct()
    {
        $this->court_addresses_model = new CourtAddressesModel();
    }
    
    public function handleGetAllAddresses(Request $request, Response $response)
    {
        //echo "hi"; exit;
        $filters = $request->getQueryParams();
        $court_addresses_model = new CourtAddressesModel();
        $data = $court_addresses_model->handleGetAllAddresses($filters);
        return $this->prepareOkResponse($response,$data);
    }

    public function handleGetAddressById(Request $request, Response $response, array $args)
    {
        $filters = $request->getQueryParams();
        $court_addresses_model = new CourtAddressesModel();
        $address_id = $args["address_id"];
        $data = $court_addresses_model->handleGetAddressById($address_id);
        return $this->prepareOkResponse($response,$data);
    }





}
