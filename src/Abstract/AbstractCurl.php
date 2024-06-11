<?php

namespace LTL\Curl\Abstract;

use LTL\Curl\Interfaces\CurlInterface;
use LTL\Curl\Interfaces\CurlRequestInterface;
use LTL\Curl\Interfaces\CurlResponseInterface;

abstract class AbstractCurl implements CurlInterface
{
    protected CurlRequestInterface $request;

    protected CurlResponseInterface $response;

    public function __construct(string|null $uri = null)
    {
        $this->request = $this->initRequest($uri);
    }

    abstract protected function initRequest(string|null $uri): CurlRequestInterface;

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

    public function addCookies(array $cookies): self
    {
        $this->request->addCookies($cookies);

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

    public function addQueries(array $queries): self
    {
        $this->request->addQueries($queries);

        return $this;
    }

    public function withHeaders(): self
    {
        $this->request->withHeaders();

        return $this;
    }

    public function setTimeout(int $seconds): self
    {
        $this->request->setTimeout($seconds);
      
        return $this;
    }

    public function proxySocks5(string $proxy): self
    {
        $this->request->proxySocks5($proxy);

        return $this;
    }

    public function followLocation(): self
    {
        $this->request->followLocation();
        
        return $this;
    }

    public function saveInFile(string $path): self
    {
        $this->request->saveInFile($path);
        
        return $this;
    }

    /**After Request */

    public function response(): string
    {
        return $this->response->get();
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

    /** Status */

    public function status(): int
    {
        return $this->response->status();
    }

    public function isMultiStatus(): bool
    {
        return $this->response->isMultiStatus();
    }

    /** Errors */

    public function error(): bool
    {
        return $this->response->error();
    }

    public function isTooManyCurlRequestsError(): bool
    {
        return $this->response->isTooManyCurlRequestsError();
    }

    public function isConflicError(): bool
    {
        return $this->response->isConflicError();
    }

    public function isNotFoundError(): bool
    {
        return $this->response->isNotFoundError();
    }

    public function isTimeoutError(): bool
    {
        return $this->response->isTimeoutError();
    }
}
