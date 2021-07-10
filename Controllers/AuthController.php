<?php

namespace Controllers;

use Lib\JWT;
use Models\User;
use Lib\ApiResponse;
use Valitron\Validator;
use Lib\Errors\ServerError;
use Lib\Errors\ConflictError;
use Lib\Errors\InvalidFormDataError;
use Lib\Errors\NotFoundError;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController {
    public static function signup(Request $request, Response $response, array $args): Response
    {
        // Get parsed body
        $body = (object) $request->getParsedBody();


        // Validate the user input
        $v = new Validator((array) $body);
        $v->rule('required', ['name', 'email', 'password']);
        $v->rule('email', ['email']);
        $v->rule('lengthMin', ['password'], 6);

        if (!$v->validate()) {
            $response->getBody()->write((string) new InvalidFormDataError(array_shift($v->errors())[0]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        } else if (User::where('email', $body->email)->count() != 0) {
            $response->getBody()->write((string) new ConflictError('This email is taken.'));
            return $response->withStatus(409)->withHeader('Content-Type', 'application/json');
        }


        // Create the user account
        $user = new User;
        try {
            $user->name = $body->name;
            $user->email = $body->email;
            $user->password = password_hash($body->password, PASSWORD_DEFAULT);
            $user->save();
        } catch(\Exception $e) {
            $response->getBody()->write((string) new ServerError($e->getMessage()));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }


        // Return the response
        $response->getBody()->write((string) new ApiResponse([
            'data' => $user->fresh(),
            'access_token' => JWT::generateAccessToken($user),
            'token_type' => 'bearer'
        ]));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public static function login(Request $request, Response $response, array $args): Response
    {
        // Get parsed body
        $body = (object) $request->getParsedBody();


        // Validate the user input
        $v = new Validator((array) $body);
        $v->rule('required', ['email', 'password']);
        $v->rule('email', ['email']);

        if (!$v->validate()) {
            $response->getBody()->write((string) new InvalidFormDataError(array_shift($v->errors())[0]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }


        // Get and validate the user account
        $user = User::where('email', $body->email)->first();
        if ($user == null) {
            $response->getBody()->write((string) new NotFoundError('No user was found with the email and password combination.'));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        } else if (!password_verify($body->password, $user->password)) {
            $response->getBody()->write((string) new NotFoundError('No user was found with the email and password combination.'));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }


        // Return the response
        $response->getBody()->write((string) new ApiResponse([
            'data' => $user->fresh(),
            'access_token' => JWT::generateAccessToken($user),
            'token_type' => 'bearer'
        ]));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    public static function logout(Request $request, Response $response, array $args): Response
    {
        // $validator = new Validator($request->);
        return $response;
    }

    public static function getMe(Request $request, Response $response, array $args): Response
    {
        // $validator = new Validator($request->);
        return $response;
    }

    public static function updateMe(Request $request, Response $response, array $args): Response
    {
        // $validator = new Validator($request->);
        return $response;
    }
}