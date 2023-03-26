<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use Vanier\Api\Controllers\AboutController;
use Vanier\Api\Controllers\VerdictsController;
use Vanier\Api\Controllers\CasesController;
use Vanier\Api\Controllers\DefendantsController;
use Vanier\Api\Controllers\OffendersController;
use Vanier\Api\Controllers\ProsecutorsController;

// Import the app instance into this file's scope.
global $app;

// NOTE: Add your app routes here.
// The callbacks must be implemented in a controller class.
// The Vanier\Api must be used as namespace prefix. 

// ROUTE: /
$app->get('/', [AboutController::class, 'handleAboutApi']);

// Routes : cases
$app->get('/cases/{case_id}', [CasesController::class, 'handleGetCaseById']);

//Routes for Verdicts
$app->get('/verdicts', [VerdictsController::class, 'handleGetAllVerdicts']);

// Offenders Routes
$app->get('/offenders', [OffendersController::class, 'handleGetAllOffenders']);
$app->get('/offenders/{offender_id}', [OffendersController::class, 'handleGetOffenderById']);
$app->get('/offenders/{offender_id}/defendants', [OffendersController::class, 'handleGetDefendantOfOffender']);
$app->get('/offenders/{offender_id}/cases', [OffendersController::class, 'handleGetCaseOfOffender']);

// Prosecutors Routes
$app->get('/prosecutors', [ProsecutorsController::class, 'handleGetAllProsecutors']);
$app->get('/prosecutors/{prosecutor_id}', [ProsecutorsController::class, 'handleGetProsecutorById']);

// Defendants Routes
$app->get('/defendants', [DefendantsController::class, 'handleGetAllDefendants']);
$app->get('/defendants/{defendant_id}', [DefendantsController::class, 'handleGetDefendantById']);


// ROUTE: /hello
$app->get('/hello', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Reporting! Hello there!");    
    return $response;
});