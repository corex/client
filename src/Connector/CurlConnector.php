<?php

namespace CoRex\Client\Connector;

use CoRex\Client\Base\Connector;
use CoRex\Client\Base\ConnectorRequest;
use CoRex\Client\Method;
use Exception;

class CurlConnector extends Connector
{
    private $curl;
    private $responseHeaders;
    private $status;

    /**
     * CurlConnector constructor.
     *
     * @throws Exception
     */
    public function __construct()
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

        // TODO Change to use config.
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 2);

        $this->responseHeaders = [];
        $this->status = 200;
    }

    /**
     * Call.
     *
     * @param ConnectorRequest $request
     * @return mixed
     */
    public function call(ConnectorRequest $request)
    {
        $this->request = $request;

        // Prepare headers.
        $headers = [];
        if (count($request->headers) > 0) {
            foreach ($request->headers as $name => $value) {
                $headers[] = $name . ': ' . $value;
            }
        }

        // post
        if ($request->method == Method::POST) {
            curl_setopt($this->curl, CURLOPT_POST, true);
        }

        // put
        if ($request->method == Method::PUT) {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");
            $headers[] = 'Content-Length: ' . mb_strlen($request->body);
        }

        // delete
        if ($request->method == Method::DELETE) {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        // patch
        if ($request->method == Method::PATCH) {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        }

        // options
        if ($request->method == Method::OPTIONS) {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
        }

        // Set headers.
        if (count($headers) > 0) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        }

        // Set body.
        if ($request->body != '' && $request->body !== null) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $request->body);
        }

        // Set user agent.
        if ($this->request->userAgent != '' && $this->request->userAgent !== null) {
            curl_setopt($this->curl, CURLOPT_USERAGENT, $this->request->userAgent);
        }

        // Call and handle result.
//        curl_setopt($this->curl, CURLOPT_URL, $this->buildUrl());
//        $response = curl_exec($this->curl);
//        $curlInfo = curl_getinfo($this->curl);
//        $this->status = isset($curlInfo['http_code']) ? $curlInfo['http_code'] : 0;
//        if (isset($curlInfo['header_size'])) {
//            $response = substr($response, $curlInfo['header_size']);
//        }
//        curl_close($this->curl);

//        return $response;
    }

    /**
     * Get headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * Get status.
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get curl.
     *
     * @return resource
     */
    public function getCurl()
    {
        return $this->curl;
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