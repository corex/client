<?php

namespace CoRex\Client\Base;

interface ConnectorInterface
{
    /**
     * Call.
     *
     * @param ConnectorRequest $request
     * @return mixed
     */
    public function call(ConnectorRequest $request);

    /**
     * Get headers.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Get http code.
     *
     * @return integer
     */
    public function getStatus();
}