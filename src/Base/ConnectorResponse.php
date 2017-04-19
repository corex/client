<?php

namespace CoRex\Client\Base;

class ConnectorResponse
{
    /**
     * @var mixed
     */
    public $response;

    /**
     * @var array
     */
    public $headers;

    /**
     * @var integer
     */
    public $httpCode;

    /**
     * ConnectorResponse constructor.
     *
     * @param mixed $response
     * @param array $headers
     * @param integer $httpCode
     */
    public function __construct($response, array $headers, $httpCode)
    {
        $this->response = $response;
        $this->headers = $headers;
        $this->httpCode = $httpCode;
    }
}