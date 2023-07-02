<?php

declare(strict_types=1);

namespace Timiki\RpcCommon\Http;

class Headers implements \IteratorAggregate, \Countable
{
    protected array $headers = [];

    public function __construct(array $headers = [])
    {
        foreach ($headers as $key => $values) {
            $this->set($key, $values);
        }
    }

    /**
     * Returns the headers.
     */
    public function all(): array
    {
        return $this->headers;
    }

    /**
     * Returns a header value by name.
     */
    public function get(string $key, mixed $default = null, bool $first = true): mixed
    {
        $key = \str_replace('_', '-', \mb_strtolower($key));

        if (!\array_key_exists($key, $this->headers)) {
            if (null === $default) {
                return $first ? null : [];
            }

            return $first ? $default : [$default];
        }

        if ($first) {
            return \count($this->headers[$key]) ? $this->headers[$key][0] : $default;
        }

        return $this->headers[$key];
    }

    /**
     * Sets a header by name.
     */
    public function set(string $key, mixed $values, bool $replace = true): void
    {
        $key = \str_replace('_', '-', \mb_strtolower($key));

        $values = \array_values((array) $values);

        if (true === $replace || !isset($this->headers[$key])) {
            $this->headers[$key] = $values;
        } else {
            $this->headers[$key] = \array_merge($this->headers[$key], $values);
        }
    }

    /**
     * Adds new headers the current HTTP headers set.
     */
    public function add(array $headers): void
    {
        foreach ($headers as $key => $values) {
            $this->set($key, $values);
        }
    }

    /**
     * Returns true if the HTTP header is defined.
     */
    public function has(string $key): bool
    {
        return \array_key_exists(\str_replace('_', '-', \mb_strtolower($key)), $this->headers);
    }

    /**
     * Removes a header.
     */
    public function remove(string $key): void
    {
        $key = \str_replace('_', '-', \mb_strtolower($key));

        unset($this->headers[$key]);
    }

    /**
     * Returns an iterator for headers.
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->headers);
    }

    /**
     * Returns the number of headers.
     */
    public function count(): int
    {
        return \count($this->headers);
    }
}
