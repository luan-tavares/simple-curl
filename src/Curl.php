<?php

namespace LTL\Curl;

use LTL\Curl\CurlRequest;
use LTL\Curl\Interfaces\CurlInterface;
use LTL\Curl\Interfaces\CurlResponseInterface;

class Curl implements CurlInterface
{
    private CurlRequest $request;

    private CurlResponse $response;

    public function __construct(string|null $uri = null)
    {
        $this->request = new CurlRequest($uri);
    }

    public function get(): self
    {
        return $this->request('GET');
    }

    public function post(array|null $body = null): self
    {
        return $this->request('POST', $body);
    }

    public function put(array|null $body = null): self
    {
        return $this->request('PUT', $body);
    }

    public function patch(array|null $body = null): self
    {
        return $this->request('PATCH', $body);
    }

    public function delete(): self
    {
        return $this->request('DELETE');
    }


    public function request(string $method, array|null $body = null): self
    {
        $this->response = $this->request->connect($method, $body);

        return $this;
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

    /**After Request */

    public function status(): int
    {
        return $this->response->status();
    }

    public function error(): bool
    {
        return $this->response->error();
    }

    public function isMultiStatus(): bool
    {
        return $this->response->isMultiStatus();
    }

    public function isTooManyCurlRequestsError(): bool
    {
        return $this->response->isTooManyCurlRequestsError();
    }

    public function raw(): string|null
    {
        return $this->response->raw();
    }

    public function toJson(): string|null
    {
        return $this->response->raw();
    }

    public function toArray(): array
    {
        return $this->response->toArray();
    }

    public function toObject(): object|array|null
    {
        return $this->response->toObject();
    }

    public function headers(): array|null
    {
        return $this->response->headers();
    }

    public function uri(): string
    {
        return $this->request->uri();
    }
}