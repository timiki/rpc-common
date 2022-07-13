<?php

namespace Timiki\RpcCommon;

use JsonSerializable;

class JsonResponse extends JsonHttp implements JsonSerializable
{
    /**
     * JsonRpc.
     *
     * @var string
     */
    protected $jsonrpc = '2.0';

    /**
     * Id.
     *
     * @var mixed
     */
    protected $id;

    /**
     * Method name.
     *
     * @var string
     */
    protected $method;

    /**
     * Error code.
     *
     * @var null|int|string
     */
    protected $errorCode;

    /**
     * Error message.
     *
     * @var string
     */
    protected $errorMessage;

    /**
     * Error data.
     *
     * @var null|mixed
     */
    protected $errorData;

    /**
     * Result.
     *
     * @var null|mixed
     */
    protected $result;

    /**
     * Request.
     *
     * @var null|JsonRequest
     */
    protected $request;

    /**
     * Create new JsonResponse.
     *
     * @param JsonRequest $jsonRequest
     */
    public function __construct(JsonRequest $jsonRequest = null)
    {
        if ($jsonRequest) {
            $this->setRequest($jsonRequest);
        }

        parent::__construct();
    }

    /**
     * Set request.
     *
     * @return $this
     */
    public function setRequest(JsonRequest $request)
    {
        $this->id = $request->getId();
        $this->jsonrpc = $request->getJsonrpc();
        $this->method = $request->getMethod();
        $this->request = $request;

        if (!$request->getResponse()) {
            $request->setResponse($this);
        }

        return $this;
    }

    /**
     * Get request.
     *
     * @return null|JsonRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get id.
     *
     * @return null|int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param null|int|string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get error code.
     *
     * @return null|int|string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Set error code.
     *
     * @param null|int|string $errorCode
     *
     * @return $this
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Get error message.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Set error message.
     *
     * @param string $errorMessage
     *
     * @return $this
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * Get error data.
     *
     * @return null|mixed
     */
    public function getErrorData()
    {
        return $this->errorData;
    }

    /**
     * Set error data.
     *
     * @param null|mixed $errorData
     *
     * @return $this
     */
    public function setErrorData($errorData)
    {
        $this->errorData = $errorData;

        return $this;
    }

    /**
     * Get result.
     *
     * @return null|mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set result.
     *
     * @param null|mixed $result
     *
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Return array response.
     *
     * @return array
     */
    public function getArrayResponse()
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
     *
     * @return bool
     */
    public function isError()
    {
        return null !== $this->errorCode;
    }

    /**
     * Is response from proxy.
     *
     * @return bool
     */
    public function isProxy()
    {
        return !empty($this->proxy);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @see  http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert JsonResponse to array.
     *
     * @return array
     */
    public function toArray()
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
     *
     * @return string
     */
    public function __toString()
    {
        return \json_encode($this->toArray());
    }

    /**
     * Get result (error) value.
     *
     * @param int|string $name
     * @param mixed      $default
     *
     * @return null|mixed
     */
    public function get($name, $default = null)
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
