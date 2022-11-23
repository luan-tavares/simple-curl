<?php

namespace LTL\Curl\Interfaces;

use LTL\Curl\Interfaces\CurlResponseInterface;

interface CurlInterface
{
    public function addParams(array $params): self;
    public function addHeaders(array $headers): self;
    public function addUri(string $uri): self;
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

    public function status(): int;
    public function error(): bool;
    public function isMultiStatus(): bool;
    public function isTooManyCurlRequestsError(): bool;
    public function response(): string;
    public function toArray(): array;
    public function toObject(): object|array|null;
    public function headers(): array|null;
    public function uri(): string;
}