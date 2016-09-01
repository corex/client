<?php

namespace CoRex\Client;

class HttpClient
{
    private $baseUrl;
    private $url;
    private $userAgent;
    private $headers;
    private $fieldsPath;
    private $fieldsQuery;
    private $fieldsPost;
    private $postBody;
    private $response;
    private $responseHeaders;
    private $options;
    private $curlInfo;

    /**
     * Constructor.
     *
     * HttpClient constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        // Check if curl exists.
        if (!function_exists('curl_init')) {
            throw new \Exception('Client URL Library does not exist.');
        }

        // Initialize variables.
        $this->baseUrl = '';
        $this->url = '';
        $this->userAgent = '';
        $this->headers = [];
        $this->fieldsPath = [];
        $this->fieldsQuery = [];
        $this->fieldsPost = [];
        $this->postBody = '';
        $this->response = '';
        $this->responseHeaders = [];
        $this->options = [];
        $this->curlInfo = [];
    }

    /**
     * Set url.
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->baseUrl = $url;
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
        $this->headers[$name] = $value;
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
     * Set path field.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setPathField($name, $value)
    {
        $this->fieldsPath[$name] = $value;
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
        $this->fieldsQuery[$name] = $value;
        return $this;
    }

    /**
     * Set query array (key/value).
     *
     * @param array $array
     * @return $this
     */
    public function setQueryArray(array $array)
    {
        if (is_array($array)) {
            foreach ($array as $name => $value) {
                $this->setQueryField($name, $value);
            }
        }
        return $this;
    }

    /**
     * Get query field.
     *
     * @param string $name
     * @param mixed $defaultValue Default null.
     * @return mixed
     */
    public function getQueryField($name, $defaultValue = null)
    {
        if (isset($this->fieldsQuery[$name])) {
            return $this->fieldsQuery[$name];
        }
        return $defaultValue;
    }

    /**
     * Set POST field.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setPostField($name, $value)
    {
        $this->fieldsPost[$name] = $value;
        return $this;
    }

    /**
     * Get POST field.
     *
     * @param string $name
     * @param mixed $defaultValue Default null.
     * @return mixed
     */
    public function getPostField($name, $defaultValue = null)
    {
        if (isset($this->fieldsPost[$name])) {
            return $this->fieldsPost[$name];
        }
        return $defaultValue;
    }

    /**
     * Set POST array.
     *
     * @param array $array
     * @return $this
     */
    public function setPostArray(array $array)
    {
        if (is_array($array)) {
            foreach ($array as $name => $value) {
                $this->setPostField($name, $value);
            }
        }
        return $this;
    }

    /**
     * Set POST body.
     *
     * @param string $body
     * @return $this
     */
    public function setPostBody($body)
    {
        $this->postBody = $body;
        return $this;
    }

    /**
     * Get POST body.
     *
     * @return string
     */
    public function getPostBody()
    {
        return $this->postBody;
    }

    /**
     * Return final url.
     * Note: must be called after post(), get(), put(), delete().
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get request headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get request header.
     * '
     * @param string $header
     * @param string $defaultValue Default ''.
     * @return string
     */
    public function getHeader($header, $defaultValue = '')
    {
        if (isset($this->headers[$header])) {
            return $this->headers[$header];
        }
        return $defaultValue;
    }

    /**
     * Get response headers.
     *
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * Get response.
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
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
     * Get http code.
     *
     * @return integer
     */
    public function getHttpCode()
    {
        return isset($this->curlInfo['http_code']) ? intval($this->curlInfo['http_code']) : 200;
    }

    /**
     * POST.
     *
     * @return boolean
     */
    public function post()
    {
        $this->setPostData();
        $this->setOption(CURLOPT_POST, true);
        return $this->call();
    }

    /**
     * GET.
     *
     * @return boolean
     */
    public function get()
    {
        return $this->call();
    }

    /**
     * PUT.
     *
     * @return boolean
     */
    public function put()
    {
        $postDataLength = $this->setPostData();
        $this->setOption(CURLOPT_CUSTOMREQUEST, "PUT");
        $this->setHeader('Content-Length', $postDataLength);
        return $this->call();
    }

    /**
     * DELETE.
     *
     * @return boolean
     */
    public function delete()
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, "DELETE");
        return $this->call();
    }

    /**
     * Call url.
     *
     * @return boolean
     */
    private function call()
    {
        $this->url = $this->baseUrl;

        // Replace path fields in url.
        if (count($this->fieldsPath) > 0) {
            foreach ($this->fieldsPath as $name => $value) {
                $this->url = str_replace('{' . $name . '}', $value, $this->url);
            }
        }

        // Add GET fields to url
        if (count($this->fieldsQuery) > 0) {
            $queryParts = [];
            foreach ($this->fieldsQuery as $name => $value) {
                $queryParts[] = $name . '=' . urlencode($value);
            }
            $combineChar = !is_int(strpos($this->url, '?')) ? '?' : '&';
            $this->url .= $combineChar . implode('&', $queryParts);
        }

        // Setup and call curl.
        if (count($this->headers) > 0) {
            $headers = array();
            foreach ($this->headers as $header => $value) {
                $headers[] = $header . ': ' . $value;
            }
            $this->setOption(CURLOPT_HTTPHEADER, $headers);
        }
        $this->setOption(CURLOPT_URL, $this->url);
        $this->setOption(CURLOPT_VERBOSE, true);

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
        return $this->getHttpCode() == 200;
    }

    /**
     * Handler response headers.
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
     * Set POST data and return length of data.
     *
     * @return integer
     */
    private function setPostData()
    {
        $data = '';
        if ($this->postBody != '') {
            $data = $this->postBody;
            $this->setOption(CURLOPT_POSTFIELDS, $data);
        } elseif (count($this->fieldsPost) > 0) {
            if (isset($this->headers['Content-Type']) && $this->headers['Content-Type'] == 'application/json') {
                $data = json_encode($this->fieldsPost);
                $this->setOption(CURLOPT_POSTFIELDS, $data);
            } else {
                $data = http_build_query($this->fieldsPost);
                $this->setOption(CURLOPT_POSTFIELDS, $data);
            }
        }
        return strlen($data);
    }

    /**
     * Set option.
     *
     * @param string $option
     * @param mixed $value
     */
    private function setOption($option, $value)
    {
        $this->options[$option] = $value;
    }
}