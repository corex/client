<?php

use CoRex\Client\Connector\CurlConnector;
use PHPUnit\Framework\TestCase;

class CurlConnectorTest extends TestCase
{
    /**
     * Test constructor.
     */
    public function testConstructor()
    {
        $client = $this->getConnector();
        $this->assertEquals(200, $client->getStatus());
        $this->assertEquals([], $client->getHeaders());
    }

    /**
     * Test get headers.
     */
    public function testGetHeaders()
    {
        $this->testConstructor();
    }

    /**
     * Test get status.
     */
    public function testGetStatus()
    {
        $this->testConstructor();
    }

    /**
     * Get connector.
     *
     * @return CurlConnector
     */
    private function getConnector()
    {
        return new CurlConnector();
    }
}
