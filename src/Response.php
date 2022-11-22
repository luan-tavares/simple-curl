<?php

namespace LTL\Curl;

use LTL\Curl\Curl;
use LTL\Curl\Interfaces\ResponseInterface;
use LTL\Curl\ResponseBody;
use LTL\Curl\ResponseStatus;

class Response implements ResponseInterface
{
    private ResponseStatus $status;

    private ResponseBody $body;

    private ResponseHeader $header;

    public function __construct(private Request $request)
    {
        $fullResponse = curl_exec($request->curl());

        $headerSize = curl_getinfo($request->curl(), CURLINFO_HEADER_SIZE);

        $status = curl_getinfo($request->curl(), CURLINFO_HTTP_CODE);

        $this->body = $this->makeResponseBody($fullResponse, $headerSize);

        $this->header = $this->makeResponseHeader($fullResponse, $headerSize);

        $this->status = new ResponseStatus($status);
    }

    private function makeResponseBody(string $fullResponse, int $headerSize): ResponseBody
    {
        if ($this->request->hasHeaders()) {
            return new ResponseBody(substr($fullResponse, $headerSize));
        }
        
        return new ResponseBody($fullResponse);
    }

    private function makeResponseHeader(string $fullResponse, int $headerSize): ResponseHeader
    {
        if ($this->request->hasHeaders()) {
            return new ResponseHeader(substr($fullResponse, 0, $headerSize));
        }
        
        return new ResponseHeader;
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

    public function isTooManyRequestsError(): bool
    {
        return $this->status->isTooManyRequestsError();
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