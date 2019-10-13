<?php

namespace Timiki\RpcCommon;

abstract class JsonHttp
{
    /**
     * Headers for request.
     *
     * @var Http\Headers
     */
    protected $headers;

    /**
     * Create new JsonHttp.
     */
    public function __construct()
    {
        $this->headers = new Http\Headers();
    }

    /**
     * Get http headers.
     *
     * @return Http\Headers
     */
    public function headers()
    {
        return $this->headers;
    }
}
