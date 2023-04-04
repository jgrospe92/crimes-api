<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use Vanier\Api\Controllers\AboutController;
use Vanier\Api\Controllers\VerdictsController;
use Vanier\Api\Controllers\CasesController;
use Vanier\Api\Controllers\CourtAddressesController;
use Vanier\Api\Controllers\CourtsController;

use Vanier\Api\Controllers\DefendantsController;
use Vanier\Api\Controllers\OffendersController;
use Vanier\Api\Controllers\ProsecutorsController;
use Vanier\Api\Controllers\CrimeScenesController;
use Vanier\Api\Controllers\JudgesController;
use Vanier\Api\Controllers\VictimsController;
use Vanier\Api\Controllers\OffensesController;
use Vanier\Api\Controllers\InvestigatorsController;

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
$app->get('/cases/{case_id}/victims', [CasesController::class, 'handleVictimsByCase']);
$app->get('/cases/{case_id}/offenders', [CasesController::class, 'handleOffendersByCase']);

// Routes : offenses
$app->get('/offenses', [OffensesController::class, 'handleOffenses']);
$app->get('/offenses/{offense_id}', [OffensesController::class, 'handleOffensesById']);
// Routes : investigators
$app->get('/investigators', [InvestigatorsController::class, 'handleInvestigators']);
$app->get('/investigators/{investigator_id}', [InvestigatorsController::class, 'handleInvestigatorsById']);

// Routes for Verdicts
$app->get('/verdicts', [VerdictsController::class, 'handleGetAllVerdicts']);
$app->get('/verdicts/{verdict_id}', [VerdictsController::class,'handleGetVerdictById']);
$app->post('/verdicts',[VerdictsController::class, 'handleCreateVerdicts']);

// Routes for Court_Addresses
$app->get('/court_addresses',[CourtAddressesController::class, 'handleGetAllAddresses']);
$app->get('/court_addresses/{address_id}',[CourtAddressesController::class, 'handleGetAddressById']);

// Routes for Courts
$app->get('/courts',[CourtsController::class, 'handleGetAllCourts']);
$app->get('/courts/{court_id}',[CourtsController::class, 'handleGetCourtById']);


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

$app->get('/victims', [VictimsController::class, 'handleGetAllVictims']);
$app->get('/victims/{victim_id}', [VictimsController::class, 'handleGetVictimById']);

$app->get('/judges', [JudgesController::class, 'handleGetAllJudges']);
$app->get('/judges/{judge_id}', [JudgesController::class, 'handleGetJudgeById']);

$app->get('/crime_scenes', [CrimeScenesController::class, 'handleGetAllCrimeScenes']);
$app->get('/crime_scenes/{crime_sceneID}', [CrimeScenesController::class, 'handleGetCrimeById']);