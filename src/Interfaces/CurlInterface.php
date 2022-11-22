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
    
    public function request(string $method, array|null $body = null): CurlResponseInterface;
    public function get(): CurlResponseInterface;
    public function delete(): CurlResponseInterface;
    public function post(array|null $body = null): CurlResponseInterface;
    public function put(array|null $body = null): CurlResponseInterface;
    public function patch(array|null $body = null): CurlResponseInterface;
}