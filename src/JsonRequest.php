<?php

namespace Timiki\RpcCommon;

use JsonSerializable;

class JsonRequest extends JsonHttp implements JsonSerializable
{
    /**
     * Jsonrpc version.
     *
     * @var string
     */
    protected $jsonrpc = '2.0';

    /**
     * Id.
     *
     * @var string|null
     */
    protected $id = null;

    /**
     * Method.
     *
     * @var string
     */
    protected $method;

    /**
     * Params.
     *
     * @var array
     */
    protected $params = [];

    /**
     * Response.
     *
     * @var null|JsonResponse
     */
    protected $response;

    /**
     * Create new JsonRequest.
     *
     * @param string $method
     * @param array  $params
     * @param string $id
     */
    public function __construct($method, array $params = [], $id = null)
    {
        $this->method = $method;
        $this->params = $params;
        $this->id     = $id;

        parent::__construct();
    }

    /**
     * Get jsonrpc version.
     *
     * @return string|null
     */
    public function getJsonrpc()
    {
        return $this->jsonrpc;
    }

    /**
     * Get id.
     *
     * @return integer|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get params.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get response.
     *
     * @return null|JsonResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set response.
     *
     * @param JsonResponse $response
     * @return $this
     */
    public function setResponse(JsonResponse $response)
    {
        $this->response = $response;

        if (!$response->getRequest()) {
            $response->setRequest($this);
        }

        return $this;
    }

    /**
     * Is valid.
     *
     * @return boolean
     */
    public function isValid()
    {
        if (empty($this->jsonrpc)) {
            return false;
        }

        if (empty($this->method) || !is_string($this->method)) {
            return false;
        }

        if (!empty($this->params) && !is_array($this->params)) {
            return false;
        }

        return true;
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
     * Convert JsonRequest to json string.
     *
     * @return string
     */
    public function toArray()
    {
        $json            = [];
        $json['jsonrpc'] = $this->jsonrpc;

        if ($this->method) {
            $json['method'] = $this->method;
        }

        $json['method'] = $this->method;

        if ($this->params) {
            $json['param'] = $this->params;
        }

        if ($this->id) {
            $json['id'] = $this->id;
        }

        return $json;
    }

    /**
     * Convert JsonRequest to json string.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }

    /**
     * Get request hash.
     *
     * @return string
     */
    public function getHash()
    {
        return md5($this->method.json_encode($this->params));
    }
}