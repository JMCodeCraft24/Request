<?php

declare(strict_types=1);

namespace JMCodeCraft24\Contracts;

interface RequestInterface
{
    public function getHeader(string $key): ?string;

    public function getHeaders(): array;

    public function getMethod(): string;

    public function getUri(): string;

    public function getBody(mixed $key = null): mixed;

    public function getToken(): ?string;

    public function files(): array;

    public function file(string $key): mixed;

    public function isMethod(string $method): bool;

    public function getPath(): string;

    public function getParams(): array;

    public function getParam($key): mixed;

    public function all(): array;

    public function input($key = null, $default = null): mixed;

    public function query(): array;

    public function string($key): string;

    public function boolean($key): bool;

    public function date($key, $format = 'Y-m-d', $timezone = null): ?\DateTime;

    public function isAjax(): bool;

    public function isSecure(): bool;

    public function getClientIp(): string;

    public function getUserAgent(): string;

    public function getReferer(): string;

    public function getMethodOverride(): string;

    public function hasFile(string $key): bool;
}
