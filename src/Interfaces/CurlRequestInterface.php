<?php

namespace LTL\Curl\Interfaces;

use CurlHandle;
use LTL\Curl\Interfaces\CurlResponseInterface;

interface CurlRequestInterface
{
    public function connect(string $method, array|null $body = null): CurlResponseInterface;

    public function addParams(array|string $mixed, string|int|null $value = null): self;
    public function addQueries(array $queries): self;
    public function addBody(array|null $requestBody): self;
    public function addHeaders(array|string $mixed, string|int|null $value = null): self;
    public function addCookies(array|string $mixed, string|int|null $value = null): self;
    public function addUri(string $uri): self;
    public function addMethod(string $method): self;

    public function multipartFormData(): self;
    public function formUrlEncoded(): self;
    public function progressBar(): self;

    public function hasHeaders(): bool;
    public function withHeaders(): self;
    
    public function headers(): array;
    public function body(): array|null;
    public function uri(): string;
    public function params(): array;
    public function method(): string;
    public function curl(): CurlHandle;
}
