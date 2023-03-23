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
use Vanier\Api\Validation\ValidateHelper;
// models
use Vanier\Api\models\CasesModel;

class CasesController extends BaseController
{
    private $case_model = null;
    private string $CASES_TABLE = 'cases';
    public function __construct()
    {
        $this->case_model = new CasesModel();
    }

    public function handleGetCaseById(Request $request, Response $response, array $uri_args){

        $case_id = $uri_args['case_id'];
        $filter = ['case_id' => $case_id];
        $data['data'] = $this->case_model->getCaseById($this->CASES_TABLE, $filter);
        return $this->prepareResponse($response, $data);
        
    }
}