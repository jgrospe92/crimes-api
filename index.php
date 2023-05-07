<?php


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Vanier\Api\exceptions\HttpErrorHandler;
use Vanier\Api\Helpers\JWTManager;
use Vanier\Api\middleware\ContentNegotiationMiddleware;
use Vanier\Api\middleware\LoggerMiddleware;
use Vanier\Api\Middleware\JWTAuthMiddleware;


define('APP_BASE_DIR', __DIR__);
// IMPORTANT: This file must be added to your .ignore file. 
define('APP_ENV_CONFIG', 'config.env');

define('APP_JWT_TOKEN_KEY', 'APP_JWT_TOKEN');

require __DIR__ . '/vendor/autoload.php';
// Include the file that contains the application's global configuration settings,
// database credentials, etc.
require_once __DIR__ . '/src/Config/app_config.php';

//--Step 1) Instantiate a Slim app.
$app = AppFactory::create();
// add callable
$callableResolver = $app->getCallableResolver();
$responseFactory = $app->getResponseFactory();
// add server request
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

// Parse json, form data and xml, first stack
$app->addBodyParsingMiddleware();

//-- Add the routing and body parsing middleware, second stack
$app->addRoutingMiddleware();

// logger middleware, third stack
$logger = new LoggerMiddleware();
$app->add($logger);

// AA middleware, fourth stack
$jwt_secret = JWTManager::getSecretKey();
$app->add(new JWTAuthMiddleware());

//-- Add error handling middleware.
// NOTE: the error middleware MUST be added last.
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($errorHandler);
$errorMiddleware->getDefaultErrorHandler()->forceContentType(APP_MEDIA_TYPE_JSON);

// content negotiation middleware, end of stack
$app->add(new ContentNegotiationMiddleware());



//---
// You also need to change it in .htaccess
$app->setBasePath("/crimes-api");

// Here we include the file that contains the application routes. 
// NOTE: your routes must be managed in the api_routes.php file.
require_once __DIR__ . '/src/Routes/api_routes.php';

// This is a middleware that should be disabled/enabled later. 
//$app->add($beforeMiddleware);
// Run the app.
$app->run();
