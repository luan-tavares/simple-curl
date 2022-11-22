<?php

namespace LTL\Curl\Interfaces;

interface ResponseStatusInterface
{
    public function get(): int;
    public function isMultiStatus(): bool;
    public function error(): bool;
    public function isTooManyRequestsError(): bool;
}