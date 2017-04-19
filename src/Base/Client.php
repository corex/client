<?php

namespace CoRex\Client\Base;

use CoRex\Client\Connector\CurlConnector;
use CoRex\Support\Obj;

abstract class Client
{
    private $baseUrl;
    private $tokens;
    private $parameters;
    private $headers;
    private $userAgent;

    /**
     * @var ConnectorRequest
     */
    private $connectorRequest;

    /**
     * @var ConnectorInterface
     */
    private $connector;

    /**
     * Constructor.
     *
     * @param ConnectorInterface|null $connector
     * @throws \Exception
     */
    public function __construct(ConnectorInterface $connector = null)
    {
        // Set connector.
        $this->connector = $connector;
        if ($this->connector === null) {
            $this->connector = new CurlConnector();
        }
        if (!$this->connector instanceof ConnectorInterface) {
            throw new \Exception('Connector parsed is not valid.');
        }

        $this->baseUrl = '';
        $this->tokens = [];
        $this->parameters = [];
        $this->headers = [];
        $this->userAgent = '';
        $this->connectorRequest = null;
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
     * Get connector.
     *
     * @return ConnectorInterface|CurlConnector
     * @throws \Exception
     */
    protected function getConnector()
    {
        if (!$this->connector instanceof ConnectorInterface) {
            throw new \Exception('Connector not set.');
        }
        return $this->connector;
    }

    /**
     * Call connector.
     *
     * @param RequestInterface $request
     * @return ConnectorResponse
     */
    protected function callConnector(RequestInterface $request)
    {
        // Compile connector request.
        $this->connectorRequest = new ConnectorRequest();
        $this->connectorRequest->userAgent = $this->userAgent;

        // Merge properties.
        $properties = Obj::getPropertiesFromObject(Obj::PROPERTY_PRIVATE, $request, Request::class);
        if (count($properties) > 0) {
            foreach ($properties as $name => $value) {

                // Handle method.
                if ($name == 'method') {
                    $this->connectorRequest->method = $value;
                }

                // Handle path.
                if ($name == 'path') {
                    $this->connectorRequest->url = trim($this->baseUrl, '/');
                    if ((string)$value != '') {
                        $this->connectorRequest->url .= '/' . trim($value, '/');
                    }
                }

                // Handle tokens.
                if ($name == 'tokens') {
                    $this->connectorRequest->tokens = $this->mergeProperties($this->tokens, $value);
                }

                // Handle parameters.
                if ($name == 'parameters') {
                    $this->connectorRequest->parameters = $this->mergeProperties($this->parameters, $value);
                }

                // Handle headers.
                if ($name == 'headers') {
                    $this->connectorRequest->headers = $this->mergeProperties($this->headers, $value);
                }

                // Handle body.
                if ($name == 'body') {
                    $this->connectorRequest->body = $value;
                }
            }
        }

        // Call connector.
        $connector = $this->getConnector();
        $response = $connector->call($this->connectorRequest);
        $headers = $connector->getHeaders();
        $httpCode = $connector->getStatus();
        if (!is_array($headers)) {
            $headers = [];
        }
        return new ConnectorResponse($response, $headers, $httpCode);
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
            'method' => $this->connectorRequest->method,
            'url' => $this->connectorRequest->url,
            'userAgent' => $this->connectorRequest->userAgent,
            'tokens' => $this->connectorRequest->tokens,
            'parameters' => $this->connectorRequest->parameters,
            'headers' => $this->connectorRequest->headers,
            'body' => $this->connectorRequest->body,
            'status' => $this->getConnector()->getStatus()
        ];
        return $result;
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
}