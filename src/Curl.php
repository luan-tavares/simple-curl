<?php

namespace LTL\Curl;

use LTL\Curl\Abstract\AbstractCurl;
use LTL\Curl\Interfaces\CurlRequestInterface;

class Curl extends AbstractCurl
{
    protected function initRequest(string|null $uri): CurlRequestInterface
    {
        return new CurlRequest($uri);
    }

    public function request(string $method, array|null|string $body = null): self
    {
        $this->request->connect($method, $body);

        $this->response = new CurlResponse($this->request);

        return $this;
    }
}
