<?php

namespace LTL\Curl;

use LTL\Curl\Interfaces\CurlInterface;
use LTL\Curl\Interfaces\ResponseInterface;
use LTL\Curl\Request;

class Curl implements CurlInterface
{
    private Request $request;

    public function __construct(string|null $uri = null)
    {
        $this->request = new Request($uri);
    }

    public function get(): ResponseInterface
    {
        return $this->request('GET');
    }

    public function post(array|null $body = null): ResponseInterface
    {
        return $this->request('POST', $body);
    }

    public function put(array|null $body = null): ResponseInterface
    {
        return $this->request('PUT', $body);
    }

    public function patch(array|null $body = null): ResponseInterface
    {
        return $this->request('PATCH', $body);
    }

    public function delete(): ResponseInterface
    {
        return $this->request('DELETE');
    }


    public function request(string $method, array|null $body = null): ResponseInterface
    {
        return $this->request->connect($method, $body);
    }

    public function addParams(array $params): self
    {
        $this->request->addParams($params);

        return $this;
    }

    public function multipartFormData(): self
    {
        $this->request->multipartFormData();

        return $this;
    }

    public function formUrlEncoded(): self
    {
        $this->request->formUrlEncoded();

        return $this;
    }

    public function progressBar(): self
    {
        $this->request->progressBar();

        return $this;
    }

    public function addHeaders(array $headers): self
    {
        $this->request->addHeaders($headers);

        return $this;
    }

    public function bearerToken(string $token): self
    {
        $this->request->addHeaders(['Authorization' => "Bearer {$token}"]);

        return $this;
    }

    public function addUri(string $uri): self
    {
        $this->request->addUri($uri);

        return $this;
    }

    public function withHeaders(): self
    {
        $this->request->withHeaders();

        return $this;
    }
}