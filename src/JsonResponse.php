<?php

declare(strict_types=1);

namespace Timiki\RpcCommon;

class JsonResponse extends JsonHttp implements \JsonSerializable
{
    /**
     * JsonRpc.
     */
    protected string $jsonrpc = '2.0';

    /**
     * Id.
     */
    protected string|int|float|null $id = null;

    /**
     * Method name.
     */
    protected string|null $method = null;

    /**
     * Error code.
     */
    protected int|string|null $errorCode = null;

    /**
     * Error message.
     */
    protected string|null $errorMessage = null;

    /**
     * Error data.
     */
    protected mixed $errorData = null;

    /**
     * Result.
     */
    protected mixed $result = null;

    /**
     * Request.
     */
    protected JsonRequest|null $request = null;

    public function __construct(JsonRequest $jsonRequest = null)
    {
        if ($jsonRequest) {
            $this->setRequest($jsonRequest);
        }

        parent::__construct();
    }

    /**
     * Set request.
     */
    public function setRequest(JsonRequest|null $request): self
    {
        $this->request = $request;

        $this->id = $request?->getId();
        $this->jsonrpc = $request ? $request->getJsonrpc() : '2.0';
        $this->method = $request?->getMethod();

        if (!$request->getResponse()) {
            $request->setResponse($this);
        }

        return $this;
    }

    /**
     * Get request.
     */
    public function getRequest(): JsonRequest|null
    {
        return $this->request;
    }

    /**
     * Get id.
     */
    public function getId(): string|int|float|null
    {
        return $this->id;
    }

    /**
     * Set id.
     */
    public function setId(string|int|float|null $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get error code.
     */
    public function getErrorCode(): int|string|null
    {
        return $this->errorCode;
    }

    /**
     * Set error code.
     */
    public function setErrorCode(int|string|null $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Get error message.
     */
    public function getErrorMessage(): string|null
    {
        return $this->errorMessage;
    }

    /**
     * Set error message.
     */
    public function setErrorMessage(string|null $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * Get error data.
     */
    public function getErrorData(): mixed
    {
        return $this->errorData;
    }

    /**
     * Set error data.
     */
    public function setErrorData(mixed $errorData): self
    {
        $this->errorData = $errorData;

        return $this;
    }

    /**
     * Get result.
     */
    public function getResult(): mixed
    {
        return $this->result;
    }

    /**
     * Set result.
     */
    public function setResult(mixed $result): self
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Return array response.
     */
    public function getArrayResponse(): array
    {
        $json = [];
        $json['jsonrpc'] = '2.0';

        if ($this->errorCode) {
            $json['error'] = [];
            $json['error']['code'] = $this->errorCode;
            $json['error']['message'] = $this->errorMessage;

            if (!empty($this->errorData)) {
                $json['error']['data'] = $this->errorData;
            }
        } else {
            $json['result'] = $this->result;
        }

        $json['id'] = !empty($this->id) ? $this->id : null;

        return $json;
    }

    /**
     * Is response error.
     */
    public function isError(): bool
    {
        return null !== $this->errorCode;
    }

    /**
     * Specify data which should be serialized to JSON.
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /**
     * Convert JsonResponse to array.
     */
    public function toArray(): array
    {
        $json = [];
        $json['jsonrpc'] = '2.0';

        if ($this->errorCode) {
            $json['error'] = [];
            $json['error']['code'] = $this->errorCode;
            $json['error']['message'] = $this->errorMessage;

            if (!empty($this->errorData)) {
                $json['error']['data'] = $this->errorData;
            }
        } else {
            $json['result'] = $this->result;
        }

        $json['id'] = '' !== $this->id ? $this->id : null;

        return $json;
    }

    /**
     * Convert JsonResponse to json string.
     */
    public function __toString(): string
    {
        return \json_encode($this->toArray());
    }

    /**
     * Get result (error) value.
     */
    public function get(string $name, mixed $default = null): mixed
    {
        if ($this->isError()) {
            if (\array_key_exists($name, (array) $this->getErrorData())) {
                return $this->getErrorData()[$name];
            }
        } else {
            if (\array_key_exists($name, (array) $this->getResult())) {
                return $this->getResult()[$name];
            }
        }

        return $default;
    }
}
