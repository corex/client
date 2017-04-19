<?php

namespace CoRex\Client\Rest;

use CoRex\Client\Base\Request as BaseRequest;

class Request extends BaseRequest implements RequestInterface
{
    /**
     * Request constructor.
     *
     * @param string $method
     * @param string $path Default null. If specified, added to baseUrl on client.
     */
    public function __construct($method, $path = null)
    {
        parent::__construct($method, $path);
        $this->header('Content-Type', 'application/json');
        $this->header('Accept', 'application/json');
    }
}