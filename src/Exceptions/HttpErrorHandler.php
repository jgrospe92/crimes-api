<?php
namespace Vanier\Api\exceptions;

use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler;
// Add custom exceptions
use Vanier\Api\exceptions\HttpNotAcceptableException;
use Vanier\Api\exceptions\HttpBadRequest;
use Vanier\Api\exceptions\HttpUnprocessableContent;
use Vanier\Api\exceptions\HttpNotFound;
use Vanier\Api\exceptions\HttpConflict;
use Exception;
use Throwable;

/**
 * Summary of HttpErrorHandler
 */
class HttpErrorHandler extends ErrorHandler
{
    public const BAD_REQUEST = 'BAD_REQUEST';
    public const INSUFFICIENT_PRIVILEGES = 'INSUFFICIENT_PRIVILEGES';
    public const NOT_ALLOWED = 'NOT_ALLOWED';
    public const NOT_IMPLEMENTED = 'NOT_IMPLEMENTED';
    public const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    public const SERVER_ERROR = 'SERVER_ERROR';
    public const UNAUTHENTICATED = 'UNAUTHENTICATED';
    public const NOT_ACCEPTABLE = "NOT_ACCEPTABLE";
    public const UNPROCESSABLE_CONTENT = "UNPROCESSABLE_CONTENT";
    public const CONFLICT = "CONFLICT";
    
    /**
     * Summary of respond
     * @return ResponseInterface
     */
    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;
        $statusCode = 500;
        $type = self::SERVER_ERROR;
        $description = 'An internal error has occurred while processing your request.';
        $message = 'Opps something went wrong';

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $description = $exception->getDescription();
            $message = $exception->getMessage();
            

            if ($exception instanceof HttpNotFound) {
                $type = self::RESOURCE_NOT_FOUND;
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $type = self::NOT_ALLOWED;
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpForbiddenException) {
                $type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpBadRequest) {
                $type = self::BAD_REQUEST;
            } elseif ($exception instanceof HttpNotImplementedException) {
                $type = self::NOT_IMPLEMENTED;
            } elseif ($exception instanceof HttpNotAcceptableException){
                $type = self::NOT_ACCEPTABLE;
            }elseif ($exception instanceof HttpUnprocessableContent){
                $type = self::UNPROCESSABLE_CONTENT;
            }elseif($exception instanceof HttpConflict){
                $type = self::CONFLICT;
            }
        }

        if (
            !($exception instanceof HttpException)
            && ($exception instanceof Exception || $exception instanceof Throwable)
            && $this->displayErrorDetails
        ) {
            $description = $exception->getMessage();
        }

        $error = [
            'statusCode' => $statusCode,
            'error' => [
                'type' => $type,
                'description' => $description,
                'message' => $message,
            ],
        ];
        
        $payload = json_encode($error, JSON_PRETTY_PRINT);
        
        $response = $this->responseFactory->createResponse($statusCode)->withHeader("Content-type", "application/json");        
        $response->getBody()->write($payload);
        
        return $response;
    }
}