<?php
namespace Vanier\Api\Controllers;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\controllers\BaseController;
use Vanier\Api\Models\CourtsModel;

class CourtsController extends BaseController
{
    private $courts_model = null;

    public function __construct()
    {
        $this->courts_model = new CourtsModel();
    }

    public function handleGetAllCourts(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $courts_model = new CourtsModel();
        $data = $courts_model->handleGetAllCourts($filters);
        return $this->prepareOkResponse($response, $data);
    }

    public function handleGetCourtById(Request $request, Response $response, array $args)
    {
        //echo "hi";exit;
        $filters = $request->getQueryParams();
        $courts_model = new CourtsModel();
        $court_id = $args["court_id"];
        $data = $courts_model->handleGetCourtById($court_id);
        
        return $this->prepareOkResponse($response, $data);
    }
}
