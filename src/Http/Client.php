<?php

namespace CoRex\Client\Http;

use CoRex\Client\Base\Client as BaseClient;

class Client extends BaseClient
{
    /**
     * Call connector.
     *
     * @param string $method
     * @param RequestInterface $request Default null.
     * @return Response
     */
    public function call($method, RequestInterface $request = null)
    {
        $this->callConnector($method, $request);
        return new Response(
            $this->getResponse(),
            $this->getHeaders(),
            $this->getStatus()
        );
    }
}