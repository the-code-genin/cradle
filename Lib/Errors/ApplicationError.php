<?php

namespace Lib\Errors;

class ApplicationError
{
    protected int $code;
    protected string $type, $message;

    public function __construct(int $code, string $type, string $message)
    {
        $this->code = $code;
        $this->type = $type;
        $this->message = $message;
    }

    public function __toString()
    {
        return json_encode([
            'success' => false,
            'payload' => [
                'code' => $this->code,
                'type' => $this->type,
                'message' => $this->message
            ]
        ]);
    }
}
