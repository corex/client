<?php

namespace CoRex\Client\Http;

use CoRex\Client\Base\Client as BaseClient;
use CoRex\Client\Base\RequestInterface;

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
        $response = $this->callConnector($request);
        return new Response(
            $response->response,
            $response->headers,
            $response->httpCode
        );
    }
}