<?php

use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Vanier\Api\exceptions\HttpErrorHandler;
use Vanier\Api\middleware\ContentNegotiationMiddleware;
use Vanier\Api\Models\VerdictsModel;

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
//-- Add the routing and body parsing middleware.
$app->addRoutingMiddleware();
// Parse json, form data and xml
$app->addBodyParsingMiddleware();

//-- Add error handling middleware.
// NOTE: the error middleware MUST be added last.
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($errorHandler);
$errorMiddleware->getDefaultErrorHandler()->forceContentType(APP_MEDIA_TYPE_JSON);
$app->add(new ContentNegotiationMiddleware());

// TODO: change the name of the subdirectory here.
// You also need to change it in .htaccess
$app->setBasePath("/crimes-api");

// Here we include the file that contains the application routes. 
// NOTE: your routes must be managed in the api_routes.php file.
require_once __DIR__ . '/src/Routes/api_routes.php';

// This is a middleware that should be disabled/enabled later. 
//$app->add($beforeMiddleware);
// Run the app.
$app->run();
