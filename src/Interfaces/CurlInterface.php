<?php

namespace LTL\Curl\Interfaces;

use CurlHandle;

interface CurlInterface
{
    public function addParams(array $params): self;
    public function addHeaders(array $headers): self;
    public function addUri(string $uri): self;
    public function withHeaders(): self;
    public function multipartFormData(): self;
    public function formUrlEncoded(): self;
    public function progressBar(): self;
    
    public function request(string $method, array|null $body = null): ResponseInterface;
    public function get(): ResponseInterface;
    public function delete(): ResponseInterface;
    public function post(array|null $body = null): ResponseInterface;
    public function put(array|null $body = null): ResponseInterface;
    public function patch(array|null $body = null): ResponseInterface;
}