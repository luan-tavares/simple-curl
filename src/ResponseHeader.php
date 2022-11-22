<?php

namespace LTL\Curl;

class ResponseHeader
{
    private array|null $list = null;

    public function __construct(string|null $headers = null)
    {
        if (!is_null($headers)) {
            $this->list = $this->toArray($headers);
        }
    }

    private function toArray(string $headers): array
    {
        $result = [];
        $headers = explode("\r\n", $headers);
        
        foreach ($headers as $header) {
            $split = explode(': ', $header);

            if (count($split) === 2) {
                $result[$split[0]] = $split[1];
            }
        }

        return $result;
    }

    public function get(): array|null
    {
        return $this->list;
    }

    public function value(string $key): mixed
    {
        return $this->list[$key];
    }
}