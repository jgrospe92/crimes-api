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
use Vanier\Api\Models\VerdictsModel;

/**
 * Summary of VerdictsController
 */
class VerdictsController extends BaseController
{
    private $verdicts_model = null;
    private array $filter_params = ['verdict_id', 'name', 'description', 'sentence', 'fine'];

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->verdicts_model = new VerdictsModel();
    }

    /**
     * Summary of handleGetAllVerdicts
     * @param Request $request
     * @param Response $response
     * @throws HttpUnprocessableContent
     * @throws HttpBadRequest
     * @return Response
     */
    public function handleGetAllVerdicts(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $verdicts_model = new VerdictsModel();

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

        if (isset($filters['verdict_id'])){

            if (!ValidateHelper::validateNumericInput(['verdict_id' => $filters['verdict_id']])) {
                throw new HttpBadRequest($request, "expected numeric but received alpha");
            }
        }
        if (isset($filters['fine'])){

            if (!ValidateHelper::validateNumericInput(['fine' => $filters['fine']])) {
                throw new HttpBadRequest($request, "expected numeric but received alpha");
            }
        }

        $data = $verdicts_model->handleGetAllVerdicts($filters);
        return $this->prepareOkResponse($response, $data);
    }

    /**
     * Summary of handleGetVerdictById
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @throws HttpNotFound
     * @return Response
     */
    public function handleGetVerdictById(Request $request, Response $response, array $args)
    {
      
        $filters = $request->getQueryParams();
        $verdicts_model = new VerdictsModel();
        $verdict_id = $args["verdict_id"];
        $data = $verdicts_model->handleGetVerdictById($verdict_id);

        if(!$data){
            throw new HttpNotFound($request, "please check your query parameter or consult the documentation");
        }

        return $this->prepareOkResponse($response, $data);
    }

    public function handleCreateVerdicts(Request $request, Response $response)
    {
        $verdicts_data = $request->getParsedBody();
        foreach($verdicts_data as $key =>$verdict){
            //var_dump($verdict); exit;
            $this->verdicts_model->handleCreateVerdicts($verdict);
            //var_dump($this->verdicts_model->handleCreateVerdicts($verdict));
            //echo "hi";exit;
        }
        return $response->withStatus(StatusCodeInterface::STATUS_CREATED);
    }











}
