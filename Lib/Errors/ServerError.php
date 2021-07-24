<?php

namespace Lib\Errors;

class ServerError extends ApplicationError
{
    public function __construct(string $message = 'Server Error!')
    {
        parent::__construct(500, 'ServerError', $message);
    }
}
