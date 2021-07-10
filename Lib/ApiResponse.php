<?php

namespace Lib;

class ApiResponse {
    protected array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function __toString()
    {
        return json_encode([
            'success' => true,
            'payload' => $this->payload
        ]);
    }
}