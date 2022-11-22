<?php

namespace LTL\Curl\Interfaces;

interface CurlResponseInterface
{
    public function status(): int;
    public function error(): bool;
    public function isMultiStatus(): bool;
    public function isTooManyCurlRequestsError(): bool;
    public function raw(): string|null;
    public function toJson(): string|null;
    public function toArray(): array;
    public function toObject(): object|array|null;
    public function headers(): array|null;
}