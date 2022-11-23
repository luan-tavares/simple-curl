<?php

namespace LTL\Curl;

use Error;

class CurlResponseBody
{
    public function __construct(private string $response)
    {
    }

    public function get(): string
    {
        return $this->response;
    }

    public function toArray(): array
    {
        try {
            return json_decode($this->response, true);
        } catch (Error $exception) {
            return [];
        }
    }

    public function toObject(): object|array|null
    {
        return json_decode($this->response);
    }
}