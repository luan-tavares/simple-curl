<?php

namespace LTL\Curl\Async;

use Closure;
use CurlMultiHandle;
use LTL\Curl\Async\CurlAsync;
use LTL\Curl\CurlResponse;
use ReflectionProperty;

class CurlMultiple
{
    /**
     * @var CurlAsync[]
     */
    private array $items;

    private CurlMultiHandle $multipleCurl;

    private bool $isConnected;

    public function __construct()
    {
        $this->multipleCurl = curl_multi_init();
    }

    public function add(CurlAsync $curlResource): self
    {
        $this->items[] = $curlResource;
        curl_multi_add_handle($this->multipleCurl, $curlResource->getCurl());
        
        return $this;
    }

    /**
     * @param Closure(CurlAsync $curl): void $fn
     */
    public function call(callable $fn): void
    {
        do {
            $status = curl_multi_exec($this->multipleCurl, $active);

            if ($active) {
                curl_multi_select($this->multipleCurl);
            }
        } while ($active && $status == CURLM_OK);

        foreach ($this->items as $curlResource) {
            $response = new CurlResponse($curlResource->getRequest());

            curl_multi_remove_handle($this->multipleCurl, $curlResource->getCurl());

            $reflection = new ReflectionProperty($curlResource, 'response');

            $reflection->setValue($curlResource, $response);

            $fn($curlResource);
        }
    }
}
