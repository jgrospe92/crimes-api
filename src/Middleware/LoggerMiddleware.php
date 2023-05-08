<?php

namespace Vanier\Api\middleware;

use DateTimeZone;
use Fig\Http\Message\StatusCodeInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use PDO;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Vanier\Api\Controllers\UserDBLogController;
use Vanier\Api\Helpers\AppLoggingHelper;

/**
 * Summary of ContentNegotiationMiddleware
 */
class  LoggerMiddleware implements MiddlewareInterface
{
    /**
     * Summary of __construct
     */
    public function __construct()
    {
    }

    /**
     * Summary of process
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Slim\Psr7\Response
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $status_code = $response->getStatusCode();
        $data = json_decode($response->getBody(), JSON_PRETTY_PRINT);
        $http_method = $request->getMethod();

        if ($status_code == StatusCodeInterface::STATUS_OK || $status_code == StatusCodeInterface::STATUS_CREATED) {
            switch ($http_method) {
                case 'GET':
                    $logger = AppLoggingHelper::getAccessLogger();
                    $logger->info("STATUS CODE " . $status_code, ["resource accessed successful"]);
                    break;

                case 'POST':
                    $logger = AppLoggingHelper::getCreateLogger();
                    $logger->notice("STATUS CODE " . $status_code, ["resource added successfully"]);
                    break;
                case 'PUT':
                    $logger = AppLoggingHelper::getUpdateLogger();
                    $logger->notice("STATUS CODE " . $status_code, ["resource updated successfully"]);
                    break;
                case 'DELETE':
                    $logger = AppLoggingHelper::getDeleteLogger();
                    $logger->warning("STATUS CODE " . $status_code, [$data]);
                    break;
            }
        } else {
            $logger = AppLoggingHelper::getErrorsLogger();
            $context["message"] = $data['error'];
            $logger->error("STATUS CODE " . $status_code, [$context["message"]]);
        }
        return  $response;
    }
}
