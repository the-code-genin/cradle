<?php

namespace Lib\Errors;

class InvalidFormDataError extends ApplicationError {
    public function __construct(string $message = 'Invalid form data!')
    {
        parent::__construct(400, 'InvalidFormData', $message);
    }
}