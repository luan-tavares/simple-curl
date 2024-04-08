<?php

namespace LTL\Curl\Async;

use Countable;
use CurlMultiHandle;
use Iterator;
use LTL\Curl\Async\CurlAsync;
use LTL\Curl\CurlResponse;
use ReflectionProperty;

class CurlMultiple implements Iterator, Countable
{
    /**
     * @var CurlAsync[]
     */
    private array $items;

    private bool $isConnected;

    private array|null $errors = null;

    private array $data;

    /**
     * @param CurlAsync[] $listCurlAsync
     */
    public function __construct(array|null $listCurlAsync = null)
    {
        if(is_null($listCurlAsync)) {
            return;
        }

        foreach ($listCurlAsync as $curlAsync) {
            $this->push($curlAsync);
        }
    }

    public function push(CurlAsync $curlResource): self
    {
        $this->items[] = $curlResource;
        
        
        return $this;
    }

    public function call(): self
    {
        $multipleCurl = curl_multi_init();

        foreach ($this->items as $curlAsync) {
            curl_multi_add_handle($multipleCurl, $curlAsync->getCurl());
        }

        do {
            $status = curl_multi_exec($multipleCurl, $active);

            if ($active) {
                curl_multi_select($multipleCurl);
            }
        } while ($active && $status == CURLM_OK);

        $this->isConnected = true;

        foreach ($this->items as $curlResource) {
            $response = new CurlResponse($curlResource->getRequest());

            curl_multi_remove_handle($multipleCurl, $curlResource->getCurl());

            $reflection = new ReflectionProperty($curlResource, 'response');

            $object = clone $curlResource;

            $reflection->setValue($object, $response);

            $this->data[] = $object;

            if($object->error()) {
                $this->errors[] = $object;
            }
        }
       
        return $this;
    }

    public function errors(): array|false
    {
        if(is_null($this->errors)) {
            return false;
        }
      
        return $this->errors;
    }

    public function rewind(): void
    {
        reset($this->data);
    }
    
    public function current(): CurlAsync
    {
        return current($this->data);
    }
    
    public function key(): string|null
    {
        return key($this->data);
    }
    
    public function next(): void
    {
        next($this->data);
    }
    
    public function valid(): bool
    {
        return !is_null($this->key());
    }

    /** Countable */

    public function count(): int
    {
        return count($this->data);
    }
}
