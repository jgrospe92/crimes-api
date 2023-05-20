<?php

namespace Vanier\Api\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Summary of BaseController
 */
class BaseController
{

    /**
     * Summary of prepareOkResponse
     * @param Response $response
     * @param array $data
     * @param int $status_code
     * @return Response
     * * STATIC VERSION
     */
    protected function prepareOkResponse(Response $response, array $data, int $status_code = 200)
    {
        $json_data = json_encode($data, JSON_PRETTY_PRINT);
        //-- Write data into the response's body.        
        $response->getBody()->write($json_data);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }

    /**
     * Summary of preparedResponse
     * @param Response $response
     * @param array $data
     * @param int $status_code
     * @return Response
     * * DYNAMIC VERSION
     */
    protected function preparedResponse(Response $response, array $data, int $status_code = 200)
    {
        $json_data = json_encode($data, JSON_PRETTY_PRINT);
        //-- Write data into the response's body.        
        $response->getBody()->write($json_data);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }

    /**
     * Summary of prepareErrorResponse
     * @param Response $response
     * @param array $data
     * @param mixed $status_code
     * @return Response
     * ? simple way of parsing error
     */
    protected function prepareErrorResponse(Response $response, array $data, $status_code)
    {
        $json_data = json_encode($data, JSON_PRETTY_PRINT);
        $response->getBody()->write($json_data);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
        //-- Write data into the response's body.        
    }

    /**
     * Summary of parsedError
     * @param Response $response
     * @param mixed $exception
     * @param mixed $data
     * @param mixed $status_code
     * @return Response
     * ? complex way of parsing error
     */
    protected function parsedError(Response $response, $data, $exception, $status_code)
    {
        $payload['statusCode'] = $exception->getCode();
        $payload['error']['description'] = $exception->getDescription();
        $payload['error']['message'] = $exception->getMessage();
        $payload['reason'] = $data;

        return $this->prepareErrorResponse($response, $payload, $status_code);
    }
}
