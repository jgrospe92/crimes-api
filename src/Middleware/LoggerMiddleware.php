<?php

namespace Vanier\Api\middleware;

use DateTimeZone;
use Fig\Http\Message\StatusCodeInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Vanier\Api\Helpers\AppLoggingHelper;

/**
 * Summary of ContentNegotiationMiddleware
 */
class  LoggerMiddleware implements MiddlewareInterface
{

    public const LOG_CHANNEL_ACCESS = 'ACCESS';
    public const LOG_CHANNEL_CREATE = 'CREATE';
    public const LOG_CHANNEL_UPDATE = 'UPDATE';
    public const LOG_CHANNEL_DELETE = 'DELETE';
    public const LOG_CHANNEL_ERRORS = 'ERRORS';
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
                    $logger = AppLoggingHelper::CreateAppLogger([
                       'file_path'=>  APP_LOG_FILE_ACCESS,
                       'channel_name' => self::LOG_CHANNEL_ACCESS,
                       'log_level' =>Logger::INFO
                    ]);                    
                    $logger->info("STATUS CODE " . $status_code, ["resource accessed successful"]);
                    break;

                case 'POST':
                    $logger = AppLoggingHelper::CreateAppLogger([
                        'file_path'=>  APP_LOG_FILE_POSTS,
                        'channel_name' => self::LOG_CHANNEL_CREATE,
                        'log_level' =>Logger::NOTICE
                     ]);                    
                    $logger->notice("STATUS CODE " . $status_code, [ "resource added successfully"]);
                    break;
                case 'PUT':
                    $logger = AppLoggingHelper::CreateAppLogger([
                        'file_path'=>  APP_LOG_FILE_UPDATES,
                        'channel_name' => self::LOG_CHANNEL_UPDATE,
                        'log_level' =>Logger::NOTICE
                     ]);
                    $logger->notice("STATUS CODE " . $status_code, ["resource updated successfully"]);
                    break;
                case 'DELETE':
                    $logger = AppLoggingHelper::CreateAppLogger([
                        'file_path'=>  APP_LOG_FILE_DELETES,
                        'channel_name' => self::LOG_CHANNEL_DELETE,
                        'log_level' =>Logger::WARNING
                     ]);                    
                    $logger->warning("STATUS CODE " . $status_code, [$data]);
                    break;
            }
        } else {
            $logger = AppLoggingHelper::CreateAppLogger([
                'file_path'=>  APP_LOG_FILE_ERRORS,
                'channel_name' => self::LOG_CHANNEL_ERRORS,
                'log_level' =>Logger::ERROR
             ]);  
            $context["message"] = $data['error'];
            $logger->error("STATUS CODE " . $status_code, [$context["message"]]);
        }
        return  $response;
    }
}
