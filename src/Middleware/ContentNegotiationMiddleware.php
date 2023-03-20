<?php
namespace Vanier\Api\middleware;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;



/**
 * Summary of ContentNegotiationMiddleware
 */
class  ContentNegotiationMiddleware implements MiddlewareInterface
{

    /**
     * Summary of __construct
     */
    public function __construct(){

    }
    /**
     * Summary of process
     * @param Request $request
     * @param RequestHandler $handler
     * @return Response
     */
    public function process(Request $request, RequestHandler $handler): Response
    {

        // Get the accept header
        $accept = $request->getHeaderLine("Accept");
    

        // verify if the request content type is application/json
        // if not, return a error response
        if (!str_contains(APP_MEDIA_TYPE_JSON, $accept)){

            $error_status = ["statuscode: " => StatusCodeInterface::STATUS_UNSUPPORTED_MEDIA_TYPE, "Message: " => "Invalid Media Type", "Description"=>"Request needs to be a json type" ];
            $payload = json_encode($error_status, JSON_PRETTY_PRINT);
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write($payload);
            return $response->withStatus(StatusCodeInterface::STATUS_UNSUPPORTED_MEDIA_TYPE)->withAddedHeader("Content-type", APP_MEDIA_TYPE_JSON);

        }

        // if no error, handle the response normally
        $response = $handler->handle($request);
        return  $response;
        
        }
}