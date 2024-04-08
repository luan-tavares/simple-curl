<?php

namespace LTL\Curl\Abstract;

use CurlHandle;
use LTL\Curl\CurlConstants;
use LTL\Curl\CurlProgressBar;
use LTL\Curl\CurlResponse;
use LTL\Curl\Interfaces\CurlRequestInterface;
use LTL\Curl\Interfaces\CurlResponseInterface;

abstract class AbstractCurlRequest implements CurlRequestInterface
{
    private array $params = [];

    private array $headers = [];

    private array $cookies = [];

    private array|null $queries = null;

    private array|null $body = null;

    private string $method;

    private string $uri;

    private CurlHandle $curl;

    public function __construct(string|null $uri = null)
    {
        $this->addParams(CurlConstants::CURL_PARAMS);

        $this->headers['Content-Type'] = CurlConstants::DEFAULT_CONTENT_TYPE;
 
        if (!is_null($uri)) {
            $this->addUri($uri);
        }
    }

    public function connect(string $method, array|null $body = null): void
    {
        $this->addBody($body)->addMethod($method);
        
        $this->resolveHeaders()->resolveCookies();

        $this->curl = curl_init();

        foreach ($this->params as $index => $value) {
            curl_setopt($this->curl, $index, $value);
        }
    }

    private function resolveHeaders(): self
    {
        $headersParams = [];
        foreach ($this->headers as $name => $value) {
            $name = (is_int($name))?(''):("{$name}: ");
            $headersParams[] = "{$name}{$value}";
        }
        $this->params[CURLOPT_HTTPHEADER] = $headersParams;

        return $this;
    }

    private function resolveCookies(): self
    {
        $params = [];
        foreach ($this->cookies as $name => $value) {
            $params[] = "{$name}={$value}";
        }
        $this->params[CURLOPT_COOKIE] = implode(';', $params);

        return $this;
    }

    public function withHeaders(): self
    {
        $this->params[CURLOPT_HEADER] = true;

        return $this;
    }

    public function setTimeout(int $seconds): self
    {
        $this->params[CURLOPT_TIMEOUT] = $seconds;

        return $this;
    }

    public function addParams(array|string $mixed, string|int|null $value = null): self
    {
        if (is_array($mixed)) {
            $this->params = array_replace($this->params, $mixed);

            return $this;
        }

        $this->params[$mixed] = $value;


        return $this;
    }

    public function addQueries(array $queries): self
    {
        $this->queries = $queries;

        $this->resolveUri();

        return $this;
    }

    public function addBody(array|null $requestBody): self
    {
        $this->body = $requestBody;

        if (!$this->body || empty($this->body)) {
            unset($this->params[CURLOPT_POSTFIELDS]);

            return $this;
        }

        if (@$this->headers['Content-Type'] === CurlConstants::CONTENT_TYPE_APPLICATION_JSON) {
            $this->params[CURLOPT_POSTFIELDS] = json_encode($this->body, CurlConstants::JSON_ENCODE);

            return $this;
        }

        if (@$this->headers['Content-Type'] === CurlConstants::CONTENT_TYPE_APPLICATION_FORM_URLENCODED) {
            $this->params[CURLOPT_POSTFIELDS] = http_build_query($this->body);

            return $this;
        }

        $this->params[CURLOPT_POSTFIELDS] = $this->body;

        return $this;
    }

    public function addCookies(array|string $mixed, string|int|null $value = null): self
    {
        if (is_array($mixed)) {
            $this->cookies = array_replace($this->cookies, $mixed);

            return $this;
        }

        $this->cookies[$mixed] = $value;


        return $this;
    }

    public function addHeaders(array|string $mixed, string|int|null $value = null): self
    {
        if (is_array($mixed)) {
            $this->headers = array_replace($this->headers, $mixed);

            return $this;
        }

        $this->headers[$mixed] = $value;


        return $this;
    }

    public function multipartFormData(): self
    {
        return $this->addHeaders('Content-Type', CurlConstants::CONTENT_TYPE_MULTIPART_FORM_DATA);
    }

    public function formUrlEncoded(): self
    {
        return $this->addHeaders('Content-Type', CurlConstants::CONTENT_TYPE_APPLICATION_FORM_URLENCODED);
    }

    public function progressBar(): self
    {
        return $this->addParams([
            CURLOPT_PROGRESSFUNCTION => CurlProgressBar::progress(...),
            CURLOPT_NOPROGRESS => false,
        ]);
    }

    public function addUri(string $uri): self
    {
        $this->uri = $uri;

        $this->resolveUri();

        return $this;
    }

    private function resolveUri(): void
    {
        if (!isset($this->uri)) {
            return;
        }

        if (is_null($this->queries)) {
            $this->params[CURLOPT_URL] = $this->uri;

            return;
        }

        $urlQueries = http_build_query($this->queries);

        $this->params[CURLOPT_URL] = "{$this->uri}?{$urlQueries}";

        return;
    }

    public function addMethod(string $method): self
    {
        $this->method = $method;

        $this->params[CURLOPT_CUSTOMREQUEST] = $method;

        return $this;
    }

    public function hasHeaders(): bool
    {
        return $this->params[CURLOPT_HEADER];
    }

    public function headers(): array
    {
        return $this->headers;
    }
    
    public function body(): array|null
    {
        return $this->body;
    }
    
    public function uri(): string
    {
        return $this->uri;
    }

    public function params(): array
    {
        return $this->params;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function curl(): CurlHandle
    {
        return $this->curl;
    }
}
