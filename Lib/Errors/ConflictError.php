<?php

namespace Lib\Errors;

class ConflictError extends ApplicationError
{
    public function __construct(string $message = 'The resource you are trying to create already exists!')
    {
        parent::__construct(409, 'ConflictError', $message);
    }
}
