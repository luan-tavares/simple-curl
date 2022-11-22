<?php

namespace LTL\Curl;

use LTL\Curl\CurlResponseBody;
use LTL\Curl\CurlResponseStatus;
use LTL\Curl\Interfaces\CurlResponseInterface;

class CurlResponse implements CurlResponseInterface
{
    private CurlResponseStatus $status;

    private CurlResponseBody $body;

    private CurlResponseHeader $header;

    public function __construct(private CurlRequest $request)
    {
        $fullCurlResponse = curl_exec($request->curl());

        $headerSize = curl_getinfo($request->curl(), CURLINFO_HEADER_SIZE);

        $status = curl_getinfo($request->curl(), CURLINFO_HTTP_CODE);

        $this->body = $this->makeCurlResponseBody($fullCurlResponse, $headerSize);

        $this->header = $this->makeCurlResponseHeader($fullCurlResponse, $headerSize);

        $this->status = new CurlResponseStatus($status);
    }

    private function makeCurlResponseBody(string $fullCurlResponse, int $headerSize): CurlResponseBody
    {
        if ($this->request->hasHeaders()) {
            return new CurlResponseBody(substr($fullCurlResponse, $headerSize));
        }
        
        return new CurlResponseBody($fullCurlResponse);
    }

    private function makeCurlResponseHeader(string $fullCurlResponse, int $headerSize): CurlResponseHeader
    {
        if ($this->request->hasHeaders()) {
            return new CurlResponseHeader(substr($fullCurlResponse, 0, $headerSize));
        }
        
        return new CurlResponseHeader;
    }

    public function status(): int
    {
        return $this->status->get();
    }

    public function error(): bool
    {
        return $this->status->error();
    }

    public function isMultiStatus(): bool
    {
        return $this->status->isMultiStatus();
    }

    public function isTooManyCurlRequestsError(): bool
    {
        return $this->status->isTooManyCurlRequestsError();
    }

    public function raw(): string|null
    {
        return $this->body->get();
    }

    public function toJson(): string|null
    {
        return $this->body->get();
    }

    public function toArray(): array
    {
        return $this->body->toArray();
    }

    public function toObject(): object|array|null
    {
        return $this->body->toObject();
    }

    public function headers(): array|null
    {
        return $this->header->get();
    }

    public function uri(): string
    {
        return $this->request->uri();
    }
}