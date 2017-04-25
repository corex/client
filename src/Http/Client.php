<?php

namespace CoRex\Client\Http;

use CoRex\Client\Base\Client as BaseClient;

class Client extends BaseClient
{
    /**
     * Call connector.
     *
     * @param RequestInterface $request
     * @return Response
     */
    public function call(RequestInterface $request)
    {
        $this->callConnector($request);
        return new Response(
            $this->getResponse(),
            $this->getHeaders(),
            $this->getStatus()
        );
    }
}