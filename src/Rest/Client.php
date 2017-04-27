<?php

namespace CoRex\Client\Rest;

use CoRex\Client\Base\Client as BaseClient;

class Client extends BaseClient
{
    /**
     * Call.
     *
     * @param string $method
     * @param RequestInterface $request Default null.
     * @return Response
     * @throws \Exception
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