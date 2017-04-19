<?php

use CoRex\Client\Base\Connector;
use CoRex\Client\Base\ConnectorRequest;

class TestConnector extends Connector
{
    /**
     * Call.
     *
     * @param ConnectorRequest $request
     * @return mixed
     */
    public function call(ConnectorRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Get headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return [];
    }

    /**
     * Get http code.
     *
     * @return integer
     */
    public function getStatus()
    {
        return 200;
    }
}