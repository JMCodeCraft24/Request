<?php

declare(strict_types=1);

namespace JMCodeCraft24;

use JMCodeCraft24\Contracts\RequestInterface;

class Request implements RequestInterface
{
    /**
     * An associative array of request parameters.
     *
     * @var array
     */
    protected $params = [];

    /**
     * An associative array of request headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * The HTTP request method (GET, POST, PUT, DELETE, etc.).
     *
     * @var string
     */
    private $method;

    /**
     * The URI of the request.
     *
     * @var string
     */
    private $uri;

    /**
     * An associative array of request data.
     *
     * @var array
     */
    private $data;

    /**
     * An associative array of uploaded files.
     *
     * @var array
     */
    private $files;

    public function __construct()
    {
        $this->headers = getallheaders() ?: [];
        $this->method = $_SERVER['REQUEST_METHOD'] ?: 'GET';
        $this->uri = $_SERVER['REQUEST_URI'] ?: '';
        $this->data = $_REQUEST ?: [];
        $this->params = $_GET ?: [];
        $this->files = $_FILES ?: [];
    }
    /**
     * Get a specific header value from the request.
     *
     * @param string $key The name of the header.
     *
     * @return string|null The value of the header or null if not found.
     */
    public function getHeader(string $key): ?string
    {
        return isset($this->headers[$key]) ? $this->headers[$key] : null;
    }

    /**
     * Get all request headers.
     *
     * @return array An associative array of request headers.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get the HTTP request method.
     *
     * @return string The HTTP request method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get the URI of the request.
     *
     * @return string The URI of the request.
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get request data by key or the entire data if key is not provided.
     *
     * @param string|null $key The key of the data to retrieve.
     *
     * @return mixed|array|null The data value if key is provided, or the entire data array if key is null.
     */
    public function getBody(mixed $key = null): mixed
    {
        if ($key === null) {
            return $this->data;
        }

        return $this->data[$key] ?? null;
    }

    /**
     * The function returns the value of the 'Authorization' header or null if it doesn't exist.
     * 
     * @return ?string a string value if the 'Authorization' header is set, otherwise it returns null.
     */
    public function getToken(): ?string
    {
        $token = $this->getHeader('Authorization');

        if (!$token) {
            return null;
        }

        return str_replace('Bearer ', '', $token);
    }

    /**
     * Get all uploaded files.
     *
     * @return array An associative array of uploaded files.
     */
    public function files(): array
    {
        return $this->files;
    }

    /**
     * Get a specific uploaded file by key.
     *
     * @param string $key The key of the uploaded file.
     *
     * @return mixed|null The uploaded file data or null if not found.
     */
    public function file(string $key): mixed
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Check if the request method matches the given method.
     *
     * @param string $method The method to check against (GET, POST, PUT, DELETE, etc.).
     *
     * @return bool True if the request method matches, otherwise false.
     */
    public function isMethod(string $method): bool
    {
        return $_SERVER['REQUEST_METHOD'] === strtoupper($method);
    }

    /**
     * Get the path from the request URI.
     *
     * @return string The path from the request URI.
     */
    public function getPath(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Get all request parameters.
     *
     * @return array An associative array of request parameters.
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Get a specific request parameter by key.
     *
     * @param string $key The key of the parameter.
     *
     * @return mixed|null The parameter value or null if not found.
     */
    public function getParam($key): mixed
    {
        return $this->params[$key] ?? null;
    }

    /**
     * Get all request data.
     *
     * @return array An associative array of request data.
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Get input data by key, or the entire data if key is not provided.
     *
     * @param string|null $key     The key of the input data to retrieve.
     * @param mixed|null  $default The default value to return if the key is not found.
     *
     * @return mixed|array|null The input data value if key is provided, or the entire input data array if key is null.
     */
    public function input($key = null, $default = null): mixed
    {
        if ($key === null) {
            return $this->data;
        }

        return $this->data[$key] ?? $default;
    }

    /**
     * Get query parameters.
     *
     * @return array An associative array of query parameters.
     */
    public function query(): array
    {
        return $this->params;
    }

    /**
     * Get a specific request data value as a string.
     *
     * @param string $key The key of the request data.
     *
     * @return string The request data value as a string.
     */
    public function string($key): string
    {
        return (string) ($this->data[$key] ?? '');
    }

    /**
     * Get a specific request data value as a boolean.
     *
     * @param string $key The key of the request data.
     *
     * @return bool The request data value as a boolean.
     */
    public function boolean($key): bool
    {
        return filter_var($this->data[$key] ?? false, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get a specific request data value as a date.
     *
     * @param string      $key      The key of the request data.
     * @param string      $format   The format that the value should be in.
     * @param string|null $timezone The timezone for the date.
     *
     * @return \DateTime|null The request data value as a \DateTime instance or null if not valid.
     */
    public function date($key, $format = 'Y-m-d', $timezone = null): ?\DateTime
    {
        $value = $this->data[$key] ?? null;

        if ($value === null) {
            return null;
        }

        $dateTime = \DateTime::createFromFormat($format, $value);

        if ($dateTime === false) {
            return null;
        }

        if ($timezone !== null) {
            $dateTime->setTimezone(new \DateTimeZone($timezone));
        }

        return $dateTime;
    }

    /**
     * Check if the request is an AJAX request.
     * 
     * @return bool
     * */
    public function isAjax(): bool
    {
        return isset($this->headers['X-Requested-With']) && $this->headers['X-Requested-With'] === 'XMLHttpRequest';
    }

    /**
     * Check if the request is a secure request.
     * 
     * @return bool
     * */
    public function isSecure(): bool
    {
        return isset($this->headers['HTTPS']) && $this->headers['HTTPS'] === 'on';
    }

    /**
     * Get the client IP.
     * 
     * @return string
     * */
    public function getClientIp(): string
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Get the user agent.
     * 
     * @return string
     * */
    public function getUserAgent(): string
    {
        return $this->headers['User-Agent'] ?? '';
    }

    /**
     * Get the referer URL.
     * 
     * @return string
     * */
    public function getReferer(): string
    {
        return $this->headers['Referer'] ?? '';
    }

    /**
     * Get the request method override.
     * 
     * @return string
     * */
    public function getMethodOverride(): string
    {
        return $this->data['_method'] ?? $this->getHeader('X-HTTP-Method-Override');
    }

    /**
     * Check if a file has been uploaded.
     * 
     * @param string $key
     * 
     * @return bool
     * */
    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }

    /**
     * Destructor: Unsets the properties to free up memory.
     */
    public function __destruct()
    {
        unset($this->params);
        unset($this->headers);
        unset($this->method);
        unset($this->uri);
        unset($this->data);
    }
}
