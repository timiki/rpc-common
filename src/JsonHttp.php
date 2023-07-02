<?php

declare(strict_types=1);

namespace Timiki\RpcCommon;

abstract class JsonHttp
{
    protected Http\Headers $headers;

    public function __construct()
    {
        $this->headers = new Http\Headers();
    }

    /**
     * Get http headers.
     */
    public function headers(): Http\Headers
    {
        return $this->headers;
    }
}
