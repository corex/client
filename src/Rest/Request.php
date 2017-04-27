<?php

namespace CoRex\Client\Rest;

use CoRex\Client\Base\Request as BaseRequest;

class Request extends BaseRequest implements RequestInterface
{
    /**
     * Request constructor.
     *
     * @param string $path Default null. If specified, added to baseUrl on client.
     */
    public function __construct($path = null)
    {
        parent::__construct($path);
        $this->header('Content-Type', 'application/json');
        $this->header('Accept', 'application/json');
    }

    /**
     * Set field (1st level field).
     *
     * @param string $name
     * @param mixed $value
     */
    public function field($name, $value)
    {
        $fields = json_decode($this->getBody(), true);
        if (!is_array($fields)) {
            $fields = [];
        }
        $fields[$name] = $value;
        $this->setBody(json_encode($fields, JSON_UNESCAPED_SLASHES));
    }
}