<?php

namespace LTL\Curl\Interfaces;

interface CurlResponseStatusInterface
{
    public function get(): int;
    public function isMultiStatus(): bool;

    public function error(): bool;
    public function isTooManyCurlRequestsError(): bool;
    public function isConflicError(): bool;
    public function isNotFoundError(): bool;
    public function isTimeoutError(): bool;
}