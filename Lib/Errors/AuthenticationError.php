<?php

namespace Lib\Errors;

class AuthenticationError extends ApplicationError {
    public function __construct(string $message = 'User is not Authenticated!')
    {
        parent::__construct(401, 'AuthenticationError', $message);
    }
}