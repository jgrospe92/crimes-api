<?php
namespace Vanier\Api\Controllers;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\controllers\BaseController;
use Vanier\Api\Models\VerdictsModel;


class VerdictsController extends BaseController
{
    private $verdicts_model = null;

    public function __contruct()
    {
        $this->verdicts_model = new VerdictsModel();
    }

    public function handleGetAllVerdicts(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $verdicts_model = new VerdictsModel();
        $data = $verdicts_model->handleGetAllVerdicts($filters);
        return $this->prepareOkResponse($response, $data);
    }

    public function handleGetVerdictById(Request $request, Response $response, array $args)
    {
        //echo"hi";exit;
        $filters = $request->getQueryParams();
        $verdicts_model = new VerdictsModel();
        $verdict_id = $args["verdict_id"];
        $data = $verdicts_model->handleGetVerdictById($verdict_id);
        return $this->prepareOkResponse($response, $data);
    }












}