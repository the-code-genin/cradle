<?php

namespace Lib\Errors;

class ForbiddenError extends ApplicationError {
    public function __construct(string $message = 'User is forbidden from accessing this resource!')
    {
        parent::__construct(403, 'ForbiddenError', $message);
    }
}