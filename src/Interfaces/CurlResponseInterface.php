<?php

namespace LTL\Curl\Interfaces;

interface CurlResponseInterface
{
    public function get(): string;
    public function toJson(): string|null;
    public function toArray(): array;
    public function toObject(): object|array|null;
    public function headers(): array|null;

    /** Status */
    public function status(): int;
    public function isMultiStatus(): bool;

    /** Errors */
    public function error(): bool;
    public function isTooManyCurlRequestsError(): bool;
    public function isConflicError(): bool;
    public function isNotFoundError(): bool;
    public function isTimeoutError(): bool;
}
