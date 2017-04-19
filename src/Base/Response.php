<?php

namespace CoRex\Client\Base;

abstract class Response implements ResponseInterface
{
    private $response;
    private $headers;
    private $status;

    /**
     * Response constructor.
     *
     * @param string $response
     * @param array $headers
     * @param integer $status
     */
    public function __construct($response, array $headers, $status)
    {
        $this->response = $response;
        $this->headers = $headers;
        $this->status = $status;
    }

    /**
     * Get header.
     *
     * @param string $header
     * @param string $defaultValue Default null.
     * @return mixed
     */
    public function header($header, $defaultValue = null)
    {
        if (isset($this->headers[$header])) {
            return $this->headers[$header];
        }
        return $defaultValue;
    }

    /**
     * Get headers.
     *
     * @return array
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * Get body.
     *
     * @return string
     */
    public function body()
    {
        return $this->response;
    }

    /**
     * Get status.
     *
     * @return integer
     */
    public function status()
    {
        return $this->status;
    }
}