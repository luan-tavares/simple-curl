<?php

namespace LTL\Curl\Interfaces;

interface CurlResponseStatusInterface
{
    public function get(): int;
    public function isMultiStatus(): bool;
    public function error(): bool;
    public function isTooManyCurlRequestsError(): bool;
}