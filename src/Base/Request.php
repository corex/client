<?php

namespace CoRex\Client\Base;

use CoRex\Client\Method;

abstract class Request implements RequestInterface
{
    private $method;
    private $path;
    private $tokens;
    private $parameters;
    private $headers;
    private $body;

    /**
     * Request constructor.
     *
     * @param string $method
     * @param string $path Default null. If specified, added to baseUrl on client.
     * @throws \Exception
     */
    public function __construct($method, $path = null)
    {
        if (!Method::isSupported($method)) {
            throw new \Exception('Method ' . $method . ' is not supported.');
        }
        $this->method = $method;
        $this->path = $path;
        $this->tokens = [];
        $this->parameters = [];
        $this->headers = [];
        $this->body = null;
    }

    /**
     * Set path.
     *
     * @param string $path
     * @return $this
     */
    public function path($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Set token.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function token($name, $value)
    {
        $this->tokens[$name] = $value;
        return $this;
    }

    /**
     * Set query parameter.
     *
     * @param string $name
     * @param string $value Will be urlencoded automatically.
     * @return $this
     */
    public function param($name, $value)
    {
        $this->parameters[$name] = $value;
        return $this;
    }

    /**
     * Set header.
     *
     * @param string $header
     * @param string $value
     * @return $this
     */
    public function header($header, $value)
    {
        if (!is_array($this->headers)) {
            $this->headers = [];
        }
        $this->headers[$header] = $value;
        return $this;
    }

    /**
     * Set body.
     *
     * @param string $body
     */
    protected function setBody($body)
    {
        $this->body = $body;
    }
}