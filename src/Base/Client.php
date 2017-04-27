<?php

namespace CoRex\Client\Base;

use CoRex\Client\Method;
use CoRex\Support\Obj;
use Exception;

abstract class Client
{
    private $testResponse = null;
    private $testHeaders = [];
    private $testStatus = 0;
    private $baseUrl;
    private $tokens;
    private $parameters;
    private $headers;
    private $userAgent;
    private $requestProperties;
    private $curl;
    private $responseHeaders;
    private $status;
    private $timeout;
    private $response;
    private $method;

    /**
     * Client constructor.
     *
     * @param string $baseUrl Default ''.
     * @throws Exception
     */
    public function __construct($baseUrl = '')
    {
        if (!function_exists('curl_init')) {
            throw new Exception('Client URL Library does not exist.');
        }
        $this->curl = curl_init();

        // Set generic options.
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HEADER, true);
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, [$this, 'handleResponseHeaders']);
        curl_setopt($this->curl, CURLOPT_VERBOSE, true);

        $this->responseHeaders = [];
        $this->status = 200;
        $this->timeout = 2;
        $this->response = '';

        // Set connector.
        $this->baseUrl = $baseUrl;
        $this->tokens = [];
        $this->parameters = [];
        $this->headers = [];
        $this->userAgent = '';
    }

    /**
     * Set timeout.
     *
     * @param integer $timeout
     * @return $this
     */
    public function timeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Set base url.
     * Tokens {token} is supported.
     *
     * @param string $url
     * @return $this
     */
    public function baseUrl($url)
    {
        $this->baseUrl = $url;
        return $this;
    }

    /**
     * Set token.
     *
     * @param string $name
     * @param string $value
     * @param boolean $isFinal Default false.
     * @return $this
     */
    public function token($name, $value, $isFinal = false)
    {
        $this->tokens[$name] = ['value' => $value, 'isFinal' => $isFinal];
        return $this;
    }

    /**
     * Set query parameter.
     *
     * @param string $name
     * @param string $value Will be urlencoded automatically.
     * @param boolean $isFinal Default false.
     * @return $this
     */
    public function param($name, $value, $isFinal = false)
    {
        $this->parameters[$name] = ['value' => $value, 'isFinal' => $isFinal];
        return $this;
    }

    /**
     * Set header.
     *
     * @param string $header
     * @param string $value
     * @param boolean $isFinal Default false.
     * @return $this
     */
    public function header($header, $value, $isFinal = false)
    {
        $this->headers[$header] = ['value' => $value, 'isFinal' => $isFinal];
        return $this;
    }

    /**
     * Set user agent.
     *
     * @param string $userAgent
     * @return $this
     */
    public function userAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * Call connector.
     *
     * @param string $method
     * @param object $request
     * @throws \Exception
     */
    protected function callConnector($method, $request)
    {
        if (!Method::isSupported($method)) {
            throw new \Exception('Method ' . $method . ' is not supported');
        }
        $this->method = $method;

        if ($request !== null) {
            $this->requestProperties = Obj::getPropertiesFromObject(Obj::PROPERTY_PRIVATE, $request, Request::class);
        } else {
            $this->requestProperties = [];
        }
        $headers = $this->getMergedHeaders();

        // Set timeout.
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->timeout);

        // Set method (except GET).
        if ($this->method != Method::GET) {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, strtoupper($this->method));
        }

        // post
        if ($this->method == Method::POST) {
            curl_setopt($this->curl, CURLOPT_POST, true);
        }

        // put
        if ($this->method == Method::PUT) {
            $body = (string)$this->getRequestProperty('body', '');
            $headers['Content-Length'] = mb_strlen($body);
        }

        // Set headers.
        if (count($headers) > 0) {
            $requestHeaders = [];
            foreach ($headers as $name => $value) {
                $requestHeaders[] = $name . ': ' . $value;
            }
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        }

        // Set body.
        $body = (string)$this->getRequestProperty('body', '');
        if ($body != '') {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body);
        }

        // Set user agent.
        if ($this->userAgent != '') {
            curl_setopt($this->curl, CURLOPT_USERAGENT, $this->userAgent);
        }

        // Call and handle result.
        curl_setopt($this->curl, CURLOPT_URL, $this->buildUrl());
        if ($this->testResponse === null) {
            $this->response = curl_exec($this->curl);
            $curlInfo = curl_getinfo($this->curl);
            if (isset($curlInfo['header_size'])) {
                $this->response = substr($this->response, $curlInfo['header_size']);
            }
            $this->status = isset($curlInfo['http_code']) ? $curlInfo['http_code'] : 0;
        } else {
            $this->response = $this->testResponse;
            $this->responseHeaders = $this->testHeaders;
            $this->status = $this->testStatus;
        }
        curl_close($this->curl);
    }

    /**
     * Get response.
     *
     * @return string
     */
    protected function getResponse()
    {
        return $this->response;
    }

    /**
     * Get headers.
     *
     * @return array
     */
    protected function getHeaders()
    {
        $responseHeaders = $this->responseHeaders;
        if (!is_array($responseHeaders)) {
            $responseHeaders = [];
        }
        return $responseHeaders;
    }

    /**
     * Get status.
     *
     * @return integer
     */
    protected function getStatus()
    {
        return $this->status;
    }

    /**
     * Get debug information.
     * Note: response not returned.
     *
     * @return array
     */
    public function getDebug()
    {
        $result = [
            'method' => $this->method,
            'url' => $this->buildUrl(),
            'userAgent' => $this->userAgent,
            'tokens' => $this->getMergedTokens(),
            'parameters' => $this->getMergedParameters(),
            'headers' => $this->getMergedHeaders(),
            'body' => $this->getRequestProperty('body'),
            'status' => $this->getStatus()
        ];
        return $result;
    }

    /**
     * Build url.
     *
     * @return string
     */
    protected function buildUrl()
    {
        // Prepare base url.
        $url = $this->baseUrl;
        if (substr($url, -1) == '/') {
            $url = substr($url, 0, -1);
        }

        // Add path.
        $path = (string)$this->getRequestProperty('path', '');
        if ($path != '') {
            $path = (string)trim($path, '/');
            $url .= '/' . $path;
        }

        // Replace tokens.
        $tokens = $this->getMergedTokens();
        if (count($tokens) > 0) {
            foreach ($tokens as $token => $value) {
                $url = str_replace('{' . $token . '}', $value, $url);
            }
        }

        // Add parameters.
        $parameters = $this->getMergedParameters();
        $urlParameters = [];
        if (count($parameters) > 0) {
            $url .= strpos($url, '?') > 0 ? '&' : '?';
            foreach ($parameters as $name => $value) {
                $urlParameters[] = $name . '=' . urlencode($value);
            }
            $url .= implode('&', $urlParameters);
        }

        return $url;
    }

    /**
     * Get merged headers.
     *
     * @return array
     */
    private function getMergedHeaders()
    {
        return $this->mergeProperties($this->headers, $this->getRequestProperty('headers', []));
    }

    /**
     * Get merged tokens.
     *
     * @return array
     */
    private function getMergedTokens()
    {
        return $this->mergeProperties($this->tokens, $this->getRequestProperty('tokens', []));
    }

    /**
     * Get merged parameters.
     *
     * @return array
     */
    private function getMergedParameters()
    {
        return $this->mergeProperties($this->parameters, $this->getRequestProperty('parameters', []));
    }

    /**
     * Merge properties.
     *
     * @param array $clientProperties
     * @param array $requestProperties
     * @return array
     */
    private function mergeProperties(array $clientProperties, array $requestProperties)
    {
        $properties = $requestProperties;

        // Merge client properties.
        if (count($clientProperties) > 0) {
            foreach ($clientProperties as $property => $propertyProperties) {
                if (!array_key_exists($property, $properties) || $propertyProperties['isFinal']) {
                    $properties[$property] = $propertyProperties['value'];
                }
            }
        }

        return $properties;
    }

    /**
     * Get request property.
     *
     * @param string $property
     * @param mixed $defaultValue Default null.
     * @return mixed
     */
    private function getRequestProperty($property, $defaultValue = null)
    {
        if (isset($this->requestProperties[$property])) {
            return $this->requestProperties[$property];
        }
        return $defaultValue;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    /** @noinspection PhpUnusedParameterInspection */
    /**
     * Handle response headers (curl).
     *
     * @param object $curl
     * @param string $headerLine
     * @return integer
     */
    private function handleResponseHeaders($curl, $headerLine)
    {
        if (trim($headerLine) != '') {
            $pos = strpos($headerLine, ':');
            if (is_int($pos)) {
                $name = trim(substr($headerLine, 0, $pos));
                $value = trim(substr($headerLine, $pos + 1));
                $this->responseHeaders[$name] = $value;
            }
        }
        return strlen($headerLine);
    }
}