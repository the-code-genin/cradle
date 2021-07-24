<?php

namespace Lib\Errors;

class NotFoundError extends ApplicationError
{
    public function __construct(string $message = 'The resource you were looking for was not found on this server.')
    {
        parent::__construct(404, 'NotFoundError', $message);
    }
}
