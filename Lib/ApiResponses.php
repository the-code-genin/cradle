<?php

namespace Lib;

use Psr\Http\Message\ResponseInterface as Response;

class ApiResponses
{
    public static function generateApplicationError(Response $response, int $code, string $type, string $message)
    {
        $data = json_encode([
            'success' => false,
            'payload' => [
                'code' => $code,
                'type' => $type,
                'message' => $message
            ]
        ]);
        $response->getBody()->write($data);
        return $response->withStatus($code)->withHeader('Content-Type', 'application/json');
    }

    public static function generateInvalidFormDataError(Response $response, string $message = "Invalid form data!")
    {
        return ApiResponses::generateApplicationError($response, 400, "InvalidFormData", $message);
    }

    public static function generateAuthenticationError(Response $response, string $message = "User is not Authenticated!")
    {
        return ApiResponses::generateApplicationError($response, 401, "AuthenticationError", $message);
    }

    public static function generateForbiddenError(Response $response, string $message = "User is forbidden from accessing this resource!")
    {
        return ApiResponses::generateApplicationError($response, 403, "ForbiddenError", $message);
    }

    public static function generateNotFoundError(Response $response, string $message = "The resource you were looking for was not found on this server.")
    {
        return ApiResponses::generateApplicationError($response, 404, "NotFoundError", $message);
    }

    public static function generateConflictError(Response $response, string $message = "The resource you are trying to create already exists!")
    {
        return ApiResponses::generateApplicationError($response, 409, "ConflictError", $message);
    }

    public static function generateServerError(Response $response, string $message = "Server Error!")
    {
        return ApiResponses::generateApplicationError($response, 500, "ServerError", $message);
    }

    public static function generateSuccessResponse(Response $response, $payload, int $code = 200)
    {
        $data = json_encode([
            'success' => true,
            'payload' => $payload
        ]);
        $response->getBody()->write($data);
        return $response->withStatus($code)->withHeader('Content-Type', 'application/json');
    }
}
