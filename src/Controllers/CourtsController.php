<?php
namespace Vanier\Api\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\controllers\BaseController;
use Vanier\Api\exceptions\HttpBadRequest;
use Vanier\Api\exceptions\HttpConflict;
use Vanier\Api\exceptions\HttpNotFound;
use Vanier\Api\exceptions\HttpUnprocessableContent;
use Vanier\Api\Helpers\ValidateHelper;
use Vanier\Api\Models\CourtsModel;
use Vanier\Api\Models\CourtAddressesModel;
use Exception;
use Vanier\Api\Models\JudgesModel;
use Vanier\Api\Models\VerdictsModel;

/**
 * Summary of CourtsController
 */
class CourtsController extends BaseController
{
    private $courts_model = null;
    private $court_addresses_model = null;
    private $verdicts_model = null;
    private $judges_model = null;
    private array $filter_params = ['court_id', 'name', 'date', 'time', 'address_id', 'judge_id', 'verdict_id'];

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->courts_model = new CourtsModel();
        $this->court_addresses_model = new CourtAddressesModel();
        $this->verdicts_model = new VerdictsModel();
        $this->judges_model = new JudgesModel();
    }

    /**
     * Summary of handleGetAllCourts
     * @param Request $request
     * @param Response $response
     * @throws HttpUnprocessableContent
     * @throws HttpBadRequest
     * @return Response
     */
    public function handleGetAllCourts(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $courts_model = new CourtsModel();

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
        if (isset($filters['address_id'])){

            if (!ValidateHelper::validateNumericInput(['address_id' => $filters['address_id']])) {
                throw new HttpBadRequest($request, "expected numeric but received alpha");
            }
        }
        if (isset($filters['judge_id'])){

            if (!ValidateHelper::validateNumericInput(['judge_id' => $filters['judge_id']])) {
                throw new HttpBadRequest($request, "expected numeric but received alpha");
            }
        }
        if (isset($filters['time'])){
            $time = $filters['time'];
            if (!ValidateHelper::validateTimeStamp($time)){
          
                throw new HttpUnprocessableContent($request, 'Invalid time: ' . ' {' . $time . '} [hh:mm:ss]' );
            }
        }

        $data = $courts_model->handleGetAllCourts($filters);
        return $this->prepareOkResponse($response, $data);
    }

    /**
     * Summary of handleGetCourtById
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @throws HttpNotFound
     * @return Response
     */
    public function handleGetCourtById(Request $request, Response $response, array $args)
    {
        $filters = $request->getQueryParams();
        $courts_model = new CourtsModel();
        $court_id = $args["court_id"];
        if (!ValidateHelper::validateId(['id' => $court_id])) {
            throw new HttpBadRequest($request, "please enter a valid id");
        }
        $filters = $request->getQueryParams();
        if ($filters)
        {
            throw new HttpUnprocessableContent($request, "Resource does not support filtering or pagination");
        }
        $data['court'] = $courts_model->handleGetCourtById($court_id);

        if(!$data['court']){
            throw new HttpNotFound($request, "please check your query parameter or consult the documentation");
        }
        
        return $this->prepareOkResponse($response, $data);
    }

    public function handleCreateCourts(Request $request, Response $response)
    {
        $courts_data = $request->getParsedBody();
        // To check is body is correct
        if(!isset($courts_data)){
            throw new HttpBadRequest($request,"the request body is invalid");
        }

        foreach ($courts_data as $key => $courts) {
            if(!ValidateHelper::validatePostMethods($courts, "court")){
                $exception = new HttpBadRequest($request);
                return $this->parsedError($response, $courts, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }

            // to check if foreign keys exists
            if(!$this->courts_model->checkIfResourceExists('court_addresses',['address_id' => $courts['address_id']])){
                $exception = new HttpBadRequest($request);
                $exception->setDescription("address_id is Invalid");
                return $this->parsedError($response, $courts, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }
            if(!$this->courts_model->checkIfResourceExists('judges', ['judge_id' => $courts['judge_id']])){
                $exception = new HttpBadRequest($request);
                $exception->setDescription("judge_id is Invalid");
                return $this->parsedError($response, $courts, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }
            if(!$this->courts_model->checkIfResourceExists('verdicts', ['verdict_id' => $courts['verdict_id']])){
                $exception = new HttpBadRequest($request);
                $exception->setDescription("verdict_id is Invalid");
                return $this->parsedError($response, $courts, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }

            // to check if time and date are well formatted
            if(!ValidateHelper::validateDateFormat($courts['date'])){
                $exception = new HttpBadRequest($request);
                $exception->setDescription("date format is Invalid");
                return $this->parsedError($response, $courts, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }
            if(!ValidateHelper::validateTimeStamp($courts['time'])){
                $exception = new HttpBadRequest($request);
                $exception->setDescription("timestamp format is Invalid");
                return $this->parsedError($response, $courts, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }

            try {
                $this->courts_model->handleCreateCourts($courts);
            } catch (Exception $e) {
                throw new HttpConflict($request, "Remove court_id from your body");
            }
        }
        return $this->prepareOkResponse($response, $courts_data);
        //hello friend
    }

    public function handleUpdateCourtsById(Request $request, Response $response, array $args)
    {
        $court_data = $request->getParsedBody();

        // to check if body is correct
        if(!isset($court_data)){
            throw new HttpBadRequest($request, "Please provide required data");
        }

        foreach ($court_data as $key => $court) {
            if(!ValidateHelper::validatePutMethods($court, "court")){
                $exception = new HttpBadRequest($request);
                return $this->parsedError($response, $court, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }

            // to check if foreign keys exists
            if(!$this->courts_model->checkIfResourceExists('court_addresses',['address_id' => $court['address_id']])){
                $exception = new HttpBadRequest($request);
                $exception->setDescription("address_id is Invalid");
                return $this->parsedError($response, $court, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }
            if(!$this->courts_model->checkIfResourceExists('judges', ['judge_id' => $court['judge_id']])){
                $exception = new HttpBadRequest($request);
                $exception->setDescription("judge_id is Invalid");
                return $this->parsedError($response, $court, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }
            if(!$this->courts_model->checkIfResourceExists('verdicts', ['verdict_id' => $court['verdict_id']])){
                $exception = new HttpBadRequest($request);
                $exception->setDescription("verdict_id is Invalid");
                return $this->parsedError($response, $court, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }

            // to check if time and date are well formatted
            if(!ValidateHelper::validateDateFormat($court['date'])){
                $exception = new HttpBadRequest($request);
                $exception->setDescription("date format is Invalid");
                return $this->parsedError($response, $court, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }
            if(!ValidateHelper::validateTimeStamp($court['time'])){
                $exception = new HttpBadRequest($request);
                $exception->setDescription("timestamp format is Invalid");
                return $this->parsedError($response, $court, $exception, StatusCodeInterface::STATUS_BAD_REQUEST);
            }

            // to check if court_id exists
            if (!$this->courts_model->checkIfResourceExists('courts', ['court_id' => $court['court_id']])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("court_id is invalid");
                return $this->parsedError($response, $court,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }
            // to check if address_id exists
            if (!$this->court_addresses_model->checkIfResourceExists('court_addresses', ['address_id' => $court['address_id']])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("address_id is invalid");
                return $this->parsedError($response, $court,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }
            // to check if verdict_id exists
            if (!$this->verdicts_model->checkIfResourceExists('verdicts', ['verdict_id' => $court['verdict_id']])) {
                $exception = new HttpConflict($request);
                $exception->setDescription("verdict_id is invalid");
                return $this->parsedError($response, $court,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }
            // to check if judge_id exists
            if(!$this->judges_model->checkIfResourceExists('judges',['judge_id' => $court['judge_id']])){
                $exception = new HttpConflict($request);
                $exception->setDescription("judge_id is invalid");
                return $this->parsedError($response, $court,  $exception, StatusCodeInterface::STATUS_CONFLICT);
            }

            $court_id = $court['court_id'];
            unset($court['court_id']);
            $this->courts_model->handleUpdateCourtsById($court,$court_id);
        }
        if(!$response->withStatus(StatusCodeInterface::STATUS_CREATED)){
            throw new HttpBadRequest($request,"The data entered was improperly formatted");
        }
        else{
            return $this->prepareOkResponse($response,$court_data);
        }   
    }
}
