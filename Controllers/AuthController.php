<?php

namespace Controllers;

use Database\Entities\User;
use Database\Repositories\Users;
use Lib\JWT;
use Lib\ApiResponses;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Validators\AuthValidator;

class AuthController
{
    /**
     * Sign a user up.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function signup(Request $request, Response $response, array $args): Response
    {
        // Get parsed body
        $body = (object) $request->getParsedBody();

        // Data validation
        $validationError = AuthValidator::validateSignup($body);
        if ($validationError != null) {
            return ApiResponses::generateInvalidFormDataError($response, $validationError);
        } else if (Users::getUsersWithEmailCount($body->email) != 0) {
            return ApiResponses::generateConflictError($response, "This email is taken.");
        }

        // Create the user account
        $user = new User;
        try {
            $user->name = $body->name;
            $user->email = $body->email;
            $user->password = password_hash($body->password, PASSWORD_DEFAULT);
            $user = Users::insert($user);
        } catch (\Exception $e) {
            return ApiResponses::generateServerError($response, $e->getMessage());
        }

        return ApiResponses::generateSuccessResponse(
            $response,
            [
                'data' => Users::toJSON($user),
                'access_token' => JWT::generateAccessToken($user),
                'token_type' => 'bearer'
            ],
            201
        );
    }

    /**
     * Log a user in.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function login(Request $request, Response $response, array $args): Response
    {
        // Get parsed body
        $body = (object) $request->getParsedBody();

        // Data validation
        $validationError = AuthValidator::validateLogin($body);
        if ($validationError != null) {
            return ApiResponses::generateInvalidFormDataError($response, $validationError);
        }

        // Get and validate the user account
        $user = Users::getUserByEmail($body->email);
        if ($user == null) {
            return ApiResponses::generateNotFoundError($response, "No user was found with the email and password combination.");
        } else if (!password_verify($body->password, $user->password)) {
            return ApiResponses::generateInvalidFormDataError($response, "No user was found with the email and password combination.");
        }

        // Return the response
        return ApiResponses::generateSuccessResponse($response, [
            'data' => Users::toJSON($user),
            'access_token' => JWT::generateAccessToken($user),
            'token_type' => 'bearer'
        ]);
    }

    /**
     * Get an authenticated user.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function getMe(Request $request, Response $response, array $args): Response
    {
        /** @var User $user */
        $user = $request->getAttribute('authUser');

        return ApiResponses::generateSuccessResponse($response, [
            "data" => Users::toJSON($user)
        ]);
    }

    /**
     * Update a user.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function updateMe(Request $request, Response $response, array $args): Response
    {
        /** @var User $user */
        $user = $request->getAttribute('authUser');

        // Get parsed body
        $body = (object) $request->getParsedBody();

        // Validate the user input
        $validationError = AuthValidator::validateLogin($body);
        if ($validationError != null) {
            return ApiResponses::generateInvalidFormDataError($response, $validationError);
        }

        // Update the user account
        try {
            if (!empty($body->name)) $user->name = $body->name;
            if (!empty($body->password)) $user->password = password_hash($body->password, PASSWORD_DEFAULT);
            $user = Users::updateById($user->id, $user);
        } catch(\Exception $e) {
            return ApiResponses::generateServerError($response, $e->getMessage());
        }

        // Return the response
        return ApiResponses::generateSuccessResponse($response, [
            "data" => Users::toJSON($user)
        ]);
    }

    /**
     * Log a user out.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function logout(Request $request, Response $response, array $args): Response
    {
        /** @var User $user */
        $user = $request->getAttribute('authUser');

        // Get the bearer token
        $header = $request->getHeader('Authorization');
        preg_match("/^Bearer (.+)$/i", $header[0], $matches);
        $token = trim($matches[1]);

        // Store the bearer token thereby invalidating it
        try {
            Users::addUserAuthToken($user->id, $token);
            return ApiResponses::generateSuccessResponse($response);
        } catch (\Exception $e) {
            return ApiResponses::generateServerError($response, $e->getMessage());
        }
    }
}
