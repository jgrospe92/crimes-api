<?php

namespace Vanier\Api\Controllers;

use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// exceptions
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Vanier\Api\exceptions\HttpConflict;
use Vanier\Api\exceptions\HttpNotFound;
use Vanier\Api\exceptions\HttpBadRequest;
use Vanier\Api\exceptions\HttpUnprocessableContent;
use Vanier\Api\Helpers\ValidateHelper;

// helpers

class HackzillaController extends BaseController
{
    public function handlePasswordGenerator(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        // check if body is empty, throw an exception otherwise
        if (!isset($data)) {
            throw new HttpConflict($request, "Please provide required data");
        }

        // validate length
        if (!ValidateHelper::validatePasswordGen($data)) {
            $exception = new HttpConflict($request);
            $payload['statusCode'] = $exception->getCode();
            $payload['error']['description'] = $exception->getDescription();
            $payload['error']['message'] = $exception->getMessage();
            $payload['reason'] = $data;

            return $this->prepareErrorResponse($response, $payload, StatusCodeInterface::STATUS_CONFLICT);
        }

        $generator = new ComputerPasswordGenerator();

        $length = $data['length'];
        $hasLowerCase = $data['lowercase'] ? true : false;
        $hasUpperCase = $data['uppercase'] ? true : false;
        $hasRandomNumbers = $data['random_numbers'] ? true : false;
        $hasRandomSymbol = $data['random_symbols'] ? true : false;
        $generate = $data['generate'];

        $generator
            ->setUppercase($hasUpperCase)
            ->setLowercase($hasLowerCase)
            ->setNumbers($hasRandomNumbers)
            ->setSymbols($hasRandomSymbol)
            ->setLength($length);

        $format = $generate > 1 ? "passwords" : "password";
        $payload = array("message" => "Generated $generate $format");
        $password = $generator->generatePasswords($generate);
        $payload[$format] = $password;


        return $this->preparedResponse($response, $payload, StatusCodeInterface::STATUS_CREATED);
    }
}
