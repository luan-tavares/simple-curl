<?php

namespace LTL\Curl\Async;

use CurlHandle;
use LTL\Curl\Abstract\AbstractCurl;
use LTL\Curl\Interfaces\CurlRequestInterface;

class CurlAsync extends AbstractCurl
{
    protected function initRequest(string|null $uri): CurlRequestInterface
    {
        return new CurlAsyncRequest($uri);
    }

    public function request(string $method, array|null $body = null): self
    {
        $this->request->connect($method, $body);

        return $this;
    }

    public function getRequest(): CurlAsyncRequest
    {
        return $this->request;
    }

    public function getCurl(): CurlHandle
    {
        
        return $this->request->curl();
    }
}
