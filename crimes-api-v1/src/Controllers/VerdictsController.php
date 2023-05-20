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
use Vanier\Api\Models\VerdictsModel;

/**
 * Summary of VerdictsController
 */
class VerdictsController extends BaseController
{
    private $verdicts_model = null;
    private array $filter_params = ['verdict_id', 'name', 'description', 'sentence', 'fine', 'sort_by'];

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

        //var_dump($filters);exit;
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
        if (!ValidateHelper::validateId(['id' => $verdict_id])) {
            throw new HttpBadRequest($request, "please enter a valid id");
        }
        if ($filters)
        {
            throw new HttpUnprocessableContent($request, "Resource does not support filtering or pagination");
        }
        $data['verdict'] = $verdicts_model->handleGetVerdictById($verdict_id);

        if(!$data['verdict']){
            throw new HttpNotFound($request, "please check your query parameter or consult the documentation");
        }

        return $this->prepareOkResponse($response, $data);
    }

    public function handleCreateVerdicts(Request $request, Response $response)
    {
        $verdicts_data = $request->getParsedBody();
        // to check if body is correct
        if(!isset($verdicts_data)){
            throw new HttpBadRequest($request, "the request body is invalid");
        }
        
        foreach($verdicts_data as $key =>$verdicts){
            if(!ValidateHelper::validatePostMethods($verdicts,'verdict')){
                $exception = new HttpBadRequest($request);
                return $this->parsedError($response, $verdicts,$exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }
        }
        
        try{
            $this->verdicts_model->handleCreateVerdicts($verdicts);
        } catch(Exception $e){
            throw new HttpBadRequest($request, "Remove verdict_id from body");
        }

        return $this->prepareOkResponse($response,$verdicts_data);
    }

    public function handleUpdateVerdictById(Request $request, Response $response, array $args)
    {
        $verdict_data = $request->getParsedBody();
        // to check if body is correct
        if(!isset($verdict_data)){
            throw new HttpBadRequest($request, "the request body is invalid");
        }

        foreach ($verdict_data as $key => $verdict) {
            if(!ValidateHelper::validatePostMethods($verdict,'verdict')){
                $exception = new HttpBadRequest($request);
                return $this->parsedError($response, $verdict, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }

            // to check if verdict_id exists
            if (!$this->verdicts_model->checkIfResourceExists('verdicts', ['verdict_id' => $verdict['verdict_id']])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("verdict_id is invalid");
                return $this->parsedError($response, $verdict,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }

            $verdict_id = $verdict['verdict_id'];
            unset($verdict['verdict_id']);
            $this->verdicts_model->handleUpdateVerdictById($verdict, $verdict_id);
        }
        if(!$response->withStatus(StatusCodeInterface::STATUS_CREATED)){
            throw new HttpBadRequest($request,"The data entered was improperly formatted");
        }
        else{
            return $this->prepareOkResponse($response,$verdict_data);
        }
    }

    public function handleDeleteVerdict(Request $request, Response $response)
    {
        $verdict_data = $request->getParsedBody()['verdict_id'];
        $verdict_ids = ['verdict_id' => $verdict_data];

        if(empty($verdict_ids['verdict_id']) || !is_array($verdict_ids['verdict_id'])){
            throw new HttpBadRequest($request, "the request body is invalid");
        }
        if(!ValidateHelper::arrayIsUnique($verdict_ids['verdict_id'])){
            throw new HttpBadRequest($request, "id is not valid or unique");
        }
        
        foreach ($verdict_ids['verdict_id'] as $verdict_id) {
            if(!ValidateHelper::validateId(['verdict_id' => $verdict_id])){
                throw new HttpBadRequest($request, "id is not valid");
            }
            if(!$this->verdicts_model->checkIfResourceExists('verdicts', ['verdict_id' => $verdict_id])){
                throw new HttpNotFound($request, "id does not exist");
            }
        }

        $deletedCount = 0;
        foreach ($verdict_ids['verdict_id'] as $verdict_id) {
            $this->verdicts_model->handleDeleteVerdict($verdict_id);
            $deletedCount++;
        }

        $verdict_format = $deletedCount>1 ? 'verdicts' : 'verdict';
        $responseMessage = [
            'message' => "$deletedCount $verdict_format deleted successfully"
        ];

        return $this->prepareOkResponse($response, $responseMessage, StatusCodeInterface::STATUS_OK);
    }
}
