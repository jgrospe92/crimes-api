<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Vanier\Api\Controllers\AboutController;
use Vanier\Api\Controllers\VerdictsController;
use Vanier\Api\Controllers\CasesController;
use Vanier\Api\Controllers\OffensesController;

// Import the app instance into this file's scope.
global $app;

// NOTE: Add your app routes here.
// The callbacks must be implemented in a controller class.
// The Vanier\Api must be used as namespace prefix. 

// ROUTE: /
$app->get('/', [AboutController::class, 'handleAboutApi']);

// Routes : cases
$app->get('/cases/{case_id}', [CasesController::class, 'handleGetCaseById']);
$app->get('/cases', [CasesController::class, 'handleGetCases']);
$app->get('/cases/{case_id}/offenses', [CasesController::class, 'handleOffensesByCase']);

// Routes : offenses
$app->get('/offenses', [OffensesController::class, 'handleOffenses']);

//Routes for Verdicts
$app->get('/verdicts', [VerdictsController::class, 'handleGetAllVerdicts']);
$app->get('/verdicts/{verdict_id}', [VerdictsController::class,'handleGetVerdictById']);
$app->post('/verdicts',[VerdictsController::class, 'handleCreateVerdict']);


// ROUTE: /hello
$app->get('/hello', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Reporting! Hello there!");    
    return $response;
});