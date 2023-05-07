<?php

namespace Vanier\Api\Controllers;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Controllers\BaseController;
use Fig\Http\Message\StatusCodeInterface;
use Vanier\Api\Models\UserModel;
use Vanier\Api\Helpers\JWTManager;

use Vanier\Api\Exceptions\HttpBadRequest;
use Vanier\Api\Exceptions\HttpConflict;
use Vanier\Api\Exceptions\HttpNotFound;
use Vanier\Api\Exceptions\HttpUnprocessableContent;

class AuthenticationController extends BaseController
{

    public function __construct()
    {
        //$this->$UserModel = new UserModel();
    }

    // HTTP POST: URI /token 
    // Authenticates an API user and generates a JWT token.
    function handleGetToken(Request $request, Response $response, array $args)
    {
        $user_data = $request->getParsedBody();
        //var_dump($user_data);exit;
        $user_model = new UserModel();
        $jwtManager = new JWTManager();

        if (empty($user_data)) {
            throw new HttpBadRequest($request, 'No data was provided in the request.');
        }
        // The received user credentials.
        $email = $user_data["email"];
        $password = $user_data["password"];
        // Verify if the provided email address is already stored in the DB.
        $db_user = $user_model->verifyEmail($email);
        if (!$db_user) {
            throw new HttpNotFound($request, 'The provided email does not match our records.');
        }
        // Now we verify if the provided passowrd.
        $db_user = $user_model->verifyPassword($email, $password);
        if (!$db_user) {
            throw new HttpUnprocessableContent($request, 'The provided password was invalid.');
        }

        // Valid user detected => Now, we generate and return a JWT.
        // Current time stamp * 60 minutes * 60 seconds
        $jwt_user_info = [
            "user_id" => $db_user["user_id"],
            "email" => $db_user["email"],
            "role" => $db_user["role"]
        ];
        //$expires_in = time() + 60 * 60;
        $expires_in = time() + 3600; // Expires in 1 hour.
        $user_jwt = JWTManager::generateToken($jwt_user_info, $expires_in);
        //--
        $response_data = [
            'status' => 1,
            'token' => $user_jwt,
            'message' => 'User logged in successfully!',
        ];
        return $this->prepareOkResponse($response, $response_data);
    }

    // HTTP POST: URI /account 
    // Creates a new user account.
    function handleCreateUserAccount(Request $request, Response $response, array $args)
    {
        $user_data = $request->getParsedBody();
        // Verify if information about the new user to be created was included in the 
        // request.
        if (empty($user_data)) {
            throw new HttpBadRequest($request, 'No data was provided in the request.');
        }
        // Check if the role field is included in the request body
        if (!isset($user_data['role'])) {
            throw new HttpBadRequest($request, 'Role field is missing in the request.');
        }
        // Data was provided, we attempt to create an account for the user.
        $user_model = new UserModel();
        try {

            $new_user = $user_model->createUser($user_data);
        } catch (Exception $e) {
            throw new HttpConflict($request, 'Failed to create the new user. User already exists');
        }

        //--
        if (!$new_user) {
            throw new HttpConflict($request, 'Failed to create the new user.');
        } else {
            $responseData = [
                'message' => "User has been created successfully!"
            ];
            return $this->preparedResponse($response, $responseData, StatusCodeInterface::STATUS_OK);
        }
    }
}
