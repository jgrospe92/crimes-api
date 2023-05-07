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
use Vanier\Api\Handlers\PDOHandler;
use Vanier\Api\Controllers\UserDBLogController;

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
        // Get the session variables
        $user_db_log = new UserDBLogController();
        if ($_SESSION['email']) {
            $email = $_SESSION['email'];
            $user_id =  $_SESSION['user_id'];
            $logged_at =  $_SESSION['logged_at'];
        }

        if ($status_code == StatusCodeInterface::STATUS_OK || $status_code == StatusCodeInterface::STATUS_CREATED) {
            switch ($http_method) {
                case 'GET':
                    $filename = '/access.log';
                    $logger = new Logger('ACCESS');
                    $logger->setTimezone(new DateTimeZone('America/Toronto'));
                    $uniqueID = new UidProcessor();
                    $logger->pushProcessor($uniqueID);
                    $log_handler = new StreamHandler(APP_LOG_DIR . $filename, Logger::INFO);
                    $log_handler->pushProcessor(new WebProcessor());
                    $logger->pushHandler($log_handler);
                    $logger->info("STATUS CODE " . $status_code, ["context" => "resource accessed successful"]);

                    // instantiate data for logging
                    $data = ['email' => $email, 'user_action' => $uniqueID->getUid(), 'logged_at' => $logged_at, 'user_id' => $user_id];
                    $user_db_log->handleDBLogger($data);
                    break;

                case 'POST':
                    $filename = '/posts.log';
                    $logger = new Logger("CREATE");
                    $logger->setTimezone(new DateTimeZone('America/Toronto'));
                    $uniqueID = new UidProcessor();
                    $logger->pushProcessor($uniqueID);
                    $log_handler = new StreamHandler(APP_LOG_DIR . $filename, Logger::NOTICE);
                    $log_handler->pushProcessor(new WebProcessor());
                    $logger->pushHandler($log_handler);
                    $logger->notice("STATUS CODE " . $status_code, ["context" => "resource added successfully"]);

                    // instantiate data for logging
                    $data = ['email' => $email, 'user_action' => $uniqueID->getUid(), 'logged_at' => $logged_at, 'user_id' => $user_id];
                    $user_db_log->handleDBLogger($data);

                    break;
                case 'PUT':
                    $filename = '/updates.log';
                    $logger = new Logger("UPDATE");
                    $logger->setTimezone(new DateTimeZone('America/Toronto'));
                    $uniqueID = new UidProcessor();
                    $logger->pushProcessor($uniqueID);
                    $log_handler = new StreamHandler(APP_LOG_DIR . $filename, Logger::NOTICE);
                    $log_handler->pushProcessor(new WebProcessor());
                    $logger->pushHandler($log_handler);
                    $logger->notice("STATUS CODE " . $status_code, ["context" => "resource updated successfully"]);

                    // instantiate data for logging
                    $data = ['email' => $email, 'user_action' => $uniqueID->getUid(), 'logged_at' => $logged_at, 'user_id' => $user_id];
                    $user_db_log->handleDBLogger($data);


                    break;
                case 'DELETE':
                    $filename = '/deletes.log';
                    $logger = new Logger("DELETE");
                    $logger->setTimezone(new DateTimeZone('America/Toronto'));
                    $uniqueID = new UidProcessor();
                    $logger->pushProcessor($uniqueID);
                    $log_handler = new StreamHandler(APP_LOG_DIR . $filename, Logger::WARNING);
                    $log_handler->pushProcessor(new WebProcessor());
                    $logger->pushHandler($log_handler);
                    $logger->warning("STATUS CODE " . $status_code, ["context" => $data]);

                    // instantiate data for logging
                    $data = ['email' => $email, 'user_action' => $uniqueID->getUid(), 'logged_at' => $logged_at, 'user_id' => $user_id];
                    $user_db_log->handleDBLogger($data);


                    break;
            }
        } else {
            $filename = '/errors.log';
            $logger = new Logger('ERRORS');
            $logger->setTimezone(new DateTimeZone('America/Toronto'));
            $uniqueID = new UidProcessor();
            $logger->pushProcessor($uniqueID);
            $log_handler = new StreamHandler(APP_LOG_DIR . $filename, Logger::ERROR);
            $log_handler->pushProcessor(new WebProcessor());
            $context["message"] = $data['error'];
            $logger->pushHandler($log_handler);
            $logger->error("STATUS CODE " . $status_code, ["context" => $context["message"]]);

            // instantiate data for logging
            if ($_SESSION['email']) {

                $data = ['email' => $email, 'user_action' => $uniqueID->getUid(), 'logged_at' => $logged_at, 'user_id' => $user_id];
                $user_db_log->handleDBLogger($data);
            }
        }

        return  $response;
    }
}
