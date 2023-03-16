<?php

namespace LTL\Curl;

use LTL\Curl\Interfaces\CurlResponseStatusInterface;

class CurlResponseStatus implements CurlResponseStatusInterface
{
    public function __construct(private int $status)
    {
    }

    public function get(): int
    {
        return $this->status;
    }

    public function isMultiStatus(): bool
    {
        return ($this->status === CurlConstants::MULTI_STATUS);
    }
    
    public function error(): bool
    {
        return ($this->status < 200 || $this->status >= 300);
    }

    public function isTooManyCurlRequestsError(): bool
    {
        return ($this->status === CurlConstants::TOO_MANY_REQUESTS_STATUS);
    }

    public function isConflicError(): bool
    {
        return ($this->status === CurlConstants::CONFLIC_STATUS_ERROR);
    }

    public function isNotFoundError(): bool
    {
        return ($this->status === CurlConstants::NOT_FOUND_STATUS_ERROR);
    }

    public function isTimeoutError(): bool
    {
        return ($this->status === CurlConstants::TIMEOUT_STATUS_ERROR);
    }
}
