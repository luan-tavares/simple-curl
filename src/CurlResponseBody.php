<?php

namespace LTL\Curl;

use Error;

class CurlResponseBody
{
    private string|null $response;

    public function __construct(string $rawCurlResponse)
    {
        $this->response = (!empty($rawCurlResponse)) ? $rawCurlResponse : null;
    }

    public function get(): string|null
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
        if (is_null($this->response)) {
            return null;
        }

        return json_decode($this->response);
    }
}