<?php

namespace CoRex\Client\Http;

use CoRex\Client\Base\Request as BaseRequest;

class Request extends BaseRequest implements RequestInterface
{
    /**
     * Set body.
     *
     * @param string $body
     * @return $this
     */
    public function body($body)
    {
        $this->setBody($body);
        return $this;
    }
}