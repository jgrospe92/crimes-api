<?php
namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\controllers\BaseController;
use Vanier\Api\exceptions\HttpBadRequest;
use Vanier\Api\exceptions\HttpNotFound;
use Vanier\Api\exceptions\HttpUnprocessableContent;
use Vanier\Api\Helpers\ValidateHelper;
use Vanier\Api\Models\CourtsModel;

/**
 * Summary of CourtsController
 */
class CourtsController extends BaseController
{
    private $courts_model = null;
    private array $filter_params = ['court_id', 'name', 'date', 'time', 'address_id', 'judge_id', 'verdict_id'];

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->courts_model = new CourtsModel();
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
          
                throw new HttpUnprocessableContent($request, 'Invalid time: ' . ' {' . $time . '}');
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
}
