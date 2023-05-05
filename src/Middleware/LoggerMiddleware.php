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
                    $filename = '/access.log';
                    $logger = new Logger('ACCESS');
                    $logger->setTimezone(new DateTimeZone('America/Toronto'));
                    $logger->pushProcessor(new UidProcessor());
                    $log_handler = new StreamHandler(APP_LOG_DIR . $filename, Logger::INFO);
                    $log_handler->pushProcessor(new WebProcessor());
                    $logger->pushHandler($log_handler);
                    $logger->info("STATUS CODE " . $status_code, ["context" => "resource accessed successful"]);
                    break;

                case 'POST':
                    $filename = '/posts.log';
                    $logger = new Logger("CREATE");
                    $logger->setTimezone(new DateTimeZone('America/Toronto'));
                    $logger->pushProcessor(new UidProcessor());
                    $log_handler = new StreamHandler(APP_LOG_DIR . $filename, Logger::NOTICE);
                    $log_handler->pushProcessor(new WebProcessor());
                    $logger->pushHandler($log_handler);
                    $logger->notice("STATUS CODE " . $status_code, ["context" => "resource added successfully"]);
                    break;
                case 'PUT':
                    $filename = '/updates.log';
                    $logger = new Logger("CREATE");
                    $logger->setTimezone(new DateTimeZone('America/Toronto'));
                    $logger->pushProcessor(new UidProcessor());
                    $log_handler = new StreamHandler(APP_LOG_DIR . $filename, Logger::NOTICE);
                    $log_handler->pushProcessor(new WebProcessor());
                    $logger->pushHandler($log_handler);
                    $logger->notice("STATUS CODE " . $status_code, ["context" => "resource updated successfully"]);
                    break;
                case 'DELETE':
                    $filename = '/deletes.log';
                    $logger = new Logger("CREATE");
                    $logger->setTimezone(new DateTimeZone('America/Toronto'));
                    $logger->pushProcessor(new UidProcessor());
                    $log_handler = new StreamHandler(APP_LOG_DIR . $filename, Logger::WARNING);
                    $log_handler->pushProcessor(new WebProcessor());
                    $logger->pushHandler($log_handler);
                    $logger->warning("STATUS CODE " . $status_code, ["context" => $data]);
                    break;
            }
        } else {
            $filename = '/errors.log';
            $logger = new Logger('ERRORS');
            $logger->setTimezone(new DateTimeZone('America/Toronto'));
            $logger->pushProcessor(new UidProcessor());
            $log_handler = new StreamHandler(APP_LOG_DIR . $filename, Logger::ERROR);
            $log_handler->pushProcessor(new WebProcessor());
            $context["message"] = $data['error'];
            $logger->pushHandler($log_handler);
            $logger->error("STATUS CODE " . $status_code, ["context" => $context["message"]]);
        }

        return  $response;
    }
}
