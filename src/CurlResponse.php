<?php

namespace LTL\Curl;

use LTL\Curl\CurlResponseBody;
use LTL\Curl\CurlResponseStatus;
use LTL\Curl\Interfaces\CurlRequestInterface;
use LTL\Curl\Interfaces\CurlResponseInterface;

class CurlResponse implements CurlResponseInterface
{
    private CurlResponseStatus $status;

    private CurlResponseBody $body;

    private CurlResponseHeader $header;



    public function __construct(CurlRequestInterface $request)
    {
        $rawResponse = curl_exec($request->curl());

        $headerSize = curl_getinfo($request->curl(), CURLINFO_HEADER_SIZE);
      
        $this->body = $this->makeCurlResponseBody($request, $rawResponse, $headerSize);
        
        $this->header = $this->makeCurlResponseHeader($request, $rawResponse, $headerSize);

        $status = curl_getinfo($request->curl(), CURLINFO_HTTP_CODE);

        $this->status = new CurlResponseStatus($status);
    }

    private function makeCurlResponseBody(CurlRequestInterface $request, string $rawResponse, int $headerSize): CurlResponseBody
    {
        if ($request->hasHeaders()) {
            return new CurlResponseBody(substr($rawResponse, $headerSize));
        }
        
        return new CurlResponseBody($rawResponse);
    }

    private function makeCurlResponseHeader(CurlRequestInterface $request, string $rawResponse, int $headerSize): CurlResponseHeader
    {
        if ($request->hasHeaders()) {
            return new CurlResponseHeader(substr($rawResponse, 0, $headerSize));
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

    public function get(): string
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
}