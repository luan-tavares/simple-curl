<?php

namespace LTL\Curl;

use CurlHandle;
use LTL\Curl\Interfaces\ResponseInterface;

class Request
{
    private array $params = [];

    private array $headers = [];

    private array|null $body = null;

    private string $method;

    private CurlHandle $curl;

    private string $uri;

    public function __construct(string|null $uri = null)
    {
        $this->addParams(CurlConstants::CURL_PARAMS);

        $this->headers['Content-Type'] = CurlConstants::DEFAULT_CONTENT_TYPE;
 
        if (!is_null($uri)) {
            $this->addUri($uri);
        }
    }

    public function connect(string $method, array|null $body = null): ResponseInterface
    {
        $this->addBody($body)->addMethod($method);
        
        $this->resolveHeaders();

        $this->curl = curl_init();

        foreach ($this->params as $index => $value) {
            curl_setopt($this->curl, $index, $value);
        }

        $response = new Response($this);

        curl_reset($this->curl);

        curl_close($this->curl);
  
        return $response;
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

    public function withHeaders(): self
    {
        $this->params[CURLOPT_HEADER] = true;

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

        $this->params[CURLOPT_URL] = $uri;

        return $this;
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