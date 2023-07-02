<?php

declare(strict_types=1);

namespace Timiki\RpcCommon;

class JsonRequest extends JsonHttp implements \JsonSerializable
{
    /**
     * Jsonrpc version.
     */
    protected string $jsonrpc = '2.0';

    /**
     * Id.
     */
    protected string|int|float|null $id;

    /**
     * Method.
     */
    protected string $method;

    /**
     * Params.
     */
    protected array $params = [];

    /**
     * Response.
     */
    protected JsonResponse|null $response = null;

    public function __construct(string $method, array $params = [], string|int|float $id = null)
    {
        $this->method = $method;
        $this->params = $params;
        $this->id = $id;

        parent::__construct();
    }

    /**
     * Get jsonrpc version.
     */
    public function getJsonrpc(): string
    {
        return $this->jsonrpc;
    }

    /**
     * Get id.
     */
    public function getId(): string|int|float|null
    {
        return $this->id;
    }

    /**
     * Get method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get params.
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Delete param by name.
     */
    public function delete(string $name): self
    {
        if (isset($this->params[$name])) {
            unset($this->params[$name]);
        }

        return $this;
    }

    /**
     * Set param by name.
     */
    public function set(string $name, mixed $value): self
    {
        $this->params[$name] = $value;

        return $this;
    }

    /**
     * Get param by name.
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return $this->params[$name] ?? $default;
    }

    /**
     * Get response.
     */
    public function getResponse(): JsonResponse|null
    {
        return $this->response;
    }

    /**
     * Set response.
     */
    public function setResponse(JsonResponse $response): self
    {
        $this->response = $response;

        if (!$response->getRequest()) {
            $response->setRequest($this);
        }

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON.
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /**
     * Convert JsonRequest to json string.
     */
    public function toArray(): array
    {
        $json = [];
        $json['jsonrpc'] = $this->jsonrpc;

        $json['method'] = $this->method;

        if ($this->params) {
            $json['params'] = $this->params;
        }

        if ($this->id) {
            $json['id'] = $this->id;
        }

        return $json;
    }

    /**
     * Convert JsonRequest to json string.
     */
    public function __toString(): string
    {
        return \json_encode($this);
    }

    /**
     * Get request hash.
     */
    public function getHash(): string
    {
        $params = $this->params;
        ksort($params);

        return \md5($this->method.\json_encode($this->params));
    }
}
