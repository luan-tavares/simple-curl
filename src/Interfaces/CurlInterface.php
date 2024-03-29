<?php

namespace LTL\Curl\Interfaces;

interface CurlInterface
{
    public function addParams(array $params): self;
    public function addHeaders(array $headers): self;
    public function addCookies(array $cookies): self;
    public function addUri(string $uri): self;
    public function addQueries(array $queries): self;
    public function withHeaders(): self;
    public function multipartFormData(): self;
    public function formUrlEncoded(): self;
    public function progressBar(): self;
    
    public function request(string $method, array|null $body = null): self;
    public function get(): self;
    public function delete(): self;
    public function post(array|null $body = null): self;
    public function put(array|null $body = null): self;
    public function patch(array|null $body = null): self;
 
    public function response(): string;
    public function toArray(): array;
    public function toObject(): object|array|null;
    public function headers(): array|null;
    public function uri(): string;

    /** Status */
    public function status(): int;
    public function isMultiStatus(): bool;

    /** Errors */
    public function error(): bool;
    public function isTooManyCurlRequestsError(): bool;
    public function isConflicError(): bool;
    public function isNotFoundError(): bool;
    public function isTimeoutError(): bool;
}
