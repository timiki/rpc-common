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
     * @var integer|null
     */
    protected $id = null;

    /**
     * Method name.
     *
     * @var integer|null
     */
    protected $method;

    /**
     * Error code.
     *
     * @var string
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
     * @var mixed|null
     */
    protected $errorData;

    /**
     * Result.
     *
     * @var mixed|null
     */
    protected $result;

    /**
     * Request.
     *
     * @var JsonRequest|null
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
     * @param JsonRequest|null $request
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
     * @return JsonRequest|null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get id.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param string|integer|null $id
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
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Set error code.
     *
     * @param string $errorCode
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
     * @return mixed|null
     */
    public function getErrorData()
    {
        return $this->errorData;
    }

    /**
     * Set error data.
     *
     * @param mixed|null $errorData
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
     * @return mixed|null
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set result.
     *
     * @param mixed|null $result
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
     * @return boolean
     */
    public function isError()
    {
        return $this->errorCode !== null;
    }

    /**
     * Is response from proxy.
     *
     * @return boolean
     */
    public function isProxy()
    {
        return !empty($this->proxy);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
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

        $json['id'] = !empty($this->id) ? $this->id : null;

        return $json;
    }

    /**
     * Convert JsonResponse to json string.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }

    /**
     * Get result (error) value.
     *
     * @param $name
     * @param null $default
     * @return null|mixed
     */
    public function get($name, $default = null)
    {
        if ($this->isError()) {
            if (array_key_exists($name, (array)$this->getErrorData())) {
                return $this->getErrorData()[$name];
            }
        } else {
            if (array_key_exists($name, (array)$this->getResult())) {
                return $this->getResult()[$name];
            }
        }

        return $default;
    }
}
