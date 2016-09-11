<?php

namespace CoRex\Client\Http;

class Client
{
    private $urlBase;
    private $urlFinal;
    private $urlPathTokens;
    private $urlQueryFields;
    private $userAgent;
    private $curlInfo;
    private $options;
    private $requestHeaders;
    private $requestBody;
    private $requestFields;
    private $responseHeaders;
    private $response;

    /**
     * Client constructor.
     *
     * @param string $baseUrl
     * @throws Exception
     */
    public function __construct($baseUrl = '')
    {
        // Check if curl exists.
        if (!function_exists('curl_init')) {
            throw new Exception('Client URL Library does not exist.');
        }

        $this->initialize();

        if ($baseUrl != '') {
            $this->setBaseUrl($baseUrl);
        }
    }

    /**
     * Set base url.
     * Tokens {token} will be replaced by setPathField().
     *
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->urlBase = $baseUrl;
        return $this;
    }

    /**
     * Set user agent.
     *
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * Set header.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setHeader($name, $value)
    {
        $this->requestHeaders[$name] = $value;
        return $this;
    }

    /**
     * Get content-type.
     *
     * @param string $contentType
     * @return $this
     */
    public function setContentType($contentType)
    {
        return $this->setHeader('Content-Type', $contentType);
    }

    /**
     * Set accept-type.
     *
     * @param string $acceptType
     * @return $this
     */
    public function setAcceptType($acceptType)
    {
        return $this->setHeader('Accept', $acceptType);
    }

    /**
     * Set path token.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setPathToken($name, $value)
    {
        $this->urlPathTokens[$name] = $value;
        return $this;
    }

    /**
     * Set query field.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setQueryField($name, $value)
    {
        $this->urlQueryFields[$name] = $value;
        return $this;
    }

    /**
     * Set query key values.
     *
     * @param array $keyValues
     * @return $this
     * @throws Exception
     */
    public function setQueryKeyValues(array $keyValues)
    {
        if (!is_array($keyValues)) {
            throw new Exception('Specified parameter is not an array.');
        }
        foreach ($keyValues as $key => $value) {
            $this->setQueryField($key, $value);
        }
        return $this;
    }

    /**
     * Set request field.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setRequestField($name, $value)
    {
        $this->requestFields[$name] = $value;
        return $this;
    }

    /**
     * Set request key values.
     *
     * @param array $keyValues
     * @return $this
     * @throws Exception
     */
    public function setRequestKeyValues(array $keyValues)
    {
        if (!is_array($keyValues)) {
            throw new Exception('Specified parameter is not an array.');
        }
        foreach ($keyValues as $key => $value) {
            $this->setRequestField($key, $value);
        }
        return $this;
    }

    /**
     * Set request body.
     * Note: Calling this method will take precedence over all other request fields.
     *
     * @param string $body
     * @return $this
     */
    public function setRequestBody($body)
    {
        $this->requestBody = $body;
        return $this;
    }

    /**
     * Get http code.
     *
     * @return integer
     */
    public function getHttpCode()
    {
        return isset($this->curlInfo['http_code']) ? intval($this->curlInfo['http_code']) : 200;
    }

    /**
     * Get response size.
     *
     * @return integer
     */
    public function getResponseSize()
    {
        if (isset($this->responseHeaders['Content-Length'])) {
            return intval($this->responseHeaders['Content-Length']);
        }
        return 0;
    }

    /**
     * Get response headers.
     *
     * @return mixed
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * Get response header.
     *
     * @param string $name
     * @param string $defaultValue Default ''.
     * @return string
     */
    public function getResponseHeader($name, $defaultValue = '')
    {
        if (isset($this->responseHeaders[$name])) {
            return $this->responseHeaders[$name];
        }
        return $defaultValue;
    }

    /**
     * GET.
     *
     * @param RequestInterface|null $request
     * @return mixed|null|string
     */
    public function get(RequestInterface $request = null)
    {
        return $this->call($request);
    }

    /**
     * POST.
     *
     * @param RequestInterface|null $request
     * @return mixed|null|string
     */
    public function post(RequestInterface $request = null)
    {
        $this->setPostData();
        $this->setCurlOption(CURLOPT_POST, true);
        return $this->call($request);
    }

    /**
     * PUT.
     *
     * @param RequestInterface|null $request
     * @return mixed|null|string
     */
    public function put(RequestInterface $request = null)
    {
        $postDataLength = $this->setPostData();
        $this->setCurlOption(CURLOPT_CUSTOMREQUEST, "PUT");
        $this->setHeader('Content-Length', $postDataLength);
        return $this->call($request);
    }

    /**
     * DELETE.
     *
     * @param RequestInterface|null $request
     * @return mixed|null|string
     */
    public function delete(RequestInterface $request = null)
    {
        $this->setCurlOption(CURLOPT_CUSTOMREQUEST, "DELETE");
        return $this->call($request);
    }

    /**
     * Get debug information.
     *
     * @param boolean $includeResponse Default false.
     * @return array
     */
    public function getDebug($includeResponse = false)
    {
        $result = [];
        $result['urlBase'] = $this->urlBase;
        $result['urlFinal'] = $this->urlFinal;
        $result['userAgent'] = $this->userAgent;
        $result['requestHeaders'] = $this->requestHeaders;
        $result['requestBody'] = $this->requestBody;
        $result['requestFields'] = $this->requestFields;
        $result['responseHeaders'] = $this->responseHeaders;
        $result['responseSize'] = $this->getResponseSize();
        if ($includeResponse) {
            $result['response'] = $this->response;
        }
        $result['httpCode'] = $this->getHttpCode();
        return $result;
    }

    /**
     * Call url.
     *
     * @param RequestInterface $request Default null.
     * @return mixed|null|string
     * @throws Exception
     */
    private function call(RequestInterface $request = null)
    {
        if ($this->urlBase == '') {
            throw new Exception('URL base not set.');
        }
        $this->urlFinal = $this->urlBase;

        // Parse path fields.
        if (count($this->urlPathTokens) > 0) {
            foreach ($this->urlPathTokens as $field => $value) {
                $this->urlFinal = str_replace('{' . $field . '}', $value, $this->urlFinal);
            }
        }

        // Parse query fields.
        if (count($this->urlQueryFields) > 0) {
            $this->setQueryFields($this->urlQueryFields);
        }

        // Parse request.
        if ($request !== null) {
            $properties = $request->getProperties();
            if (isset($properties['path'])) {
                $this->setPathTokens($properties['path']);
            }
            if (isset($properties['query'])) {
                $this->setQueryFields($properties['query']);
            }
            if (isset($properties['field'])) {
                $this->setRequestFields($properties['field']);
            }
        }

        // Setup and call curl.
        if (count($this->requestHeaders) > 0) {
            $headers = [];
            foreach ($this->requestHeaders as $header => $value) {
                $headers[] = $header . ': ' . $value;
            }
            $this->setCurlOption(CURLOPT_HTTPHEADER, $headers);
        }
        $this->setCurlOption(CURLOPT_URL, $this->urlFinal);
        $this->setCurlOption(CURLOPT_VERBOSE, true);

        // Initialize curl.
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HEADERFUNCTION, [$this, 'handleResponseHeaders']);
        curl_setopt_array($curl, $this->options);

        // Set user-agent.
        if ($this->userAgent != '') {
            curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
        }

        // Handle result.
        $this->response = curl_exec($curl);
        $this->curlInfo = curl_getinfo($curl);
        if (isset($this->curlInfo['header_size'])) {
            $this->response = substr($this->response, $this->curlInfo['header_size']);
        }
        curl_close($curl);

        if ($this->getHttpCode() != 200) {
            return null;
        }

        return $this->response;
    }

    /**
     * Set path fields.
     *
     * @param array $fields
     */
    private function setPathTokens($fields)
    {
        if (count($fields) == 0) {
            return;
        }
        foreach ($fields as $field => $value) {
            $this->urlFinal = str_replace('{' . $field . '}', $value, $this->urlFinal);
        }
    }

    /**
     * Set query fields.
     *
     * @param array $fields
     */
    private function setQueryFields($fields)
    {
        if (count($fields) == 0) {
            return;
        }
        $queryParts = [];
        foreach ($fields as $field => $value) {
            $queryParts[] = $field . '=' . urlencode($value);
        }
        $combineChar = !is_int(strpos($this->urlFinal, '?')) ? '?' : '&';
        $this->urlFinal .= $combineChar . implode('&', $queryParts);
    }

    /**
     * Set request fields.
     *
     * @param array $fields
     */
    private function setRequestFields(array $fields)
    {
        if (count($fields) == 0) {
            return;
        }
        foreach ($fields as $field => $value) {
            $this->requestFields[$field] = $value;
        }
    }

    /**
     * Set POST data and return length of data.
     *
     * @return integer
     */
    private function setPostData()
    {
        $data = '';
        if ($this->requestBody != '') {
            $data = $this->requestBody;
            $this->setCurlOption(CURLOPT_POSTFIELDS, $data);
        } elseif (count($this->requestFields) > 0) {
            if (isset($this->requestHeaders['Content-Type']) && $this->requestHeaders['Content-Type'] == 'application/json') {
                $data = json_encode($this->requestFields);
                $this->setCurlOption(CURLOPT_POSTFIELDS, $data);
            } else {
                $data = http_build_query($this->requestFields);
                $this->setCurlOption(CURLOPT_POSTFIELDS, $data);
            }
        }
        return strlen($data);
    }

    /**
     * Set curl option.
     * @param $option
     * @param $value
     */
    private function setCurlOption($option, $value)
    {
        $this->options[$option] = $value;
    }

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

    /**
     * Initialize.
     */
    private function initialize()
    {
        $this->urlBase = '';
        $this->urlFinal = '';
        $this->urlPathTokens = [];
        $this->urlQueryFields = [];
        $this->userAgent = '';
        $this->curlInfo = [];
        $this->options = [];
        $this->requestHeaders = [];
        $this->requestBody = '';
        $this->requestFields = [];
        $this->responseHeaders = [];
        $this->response = null;
    }
}