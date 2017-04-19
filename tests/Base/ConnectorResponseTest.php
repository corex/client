<?php

use CoRex\Client\Base\ConnectorResponse;
use PHPUnit\Framework\TestCase;

class ConnectorResponseTest extends TestCase
{
    /**
     * Test constructor response.
     */
    public function testConstructorResponse()
    {
        $check = md5(microtime(true));
        $response = new ConnectorResponse($check, [], 200);
        $this->assertEquals($check, $response->response);
    }

    /**
     * Test header default value.
     */
    public function testHeaderDefaultValue()
    {
        $response = new ConnectorResponse(null, [], 200);
        $this->assertEquals([], $response->headers);
    }

    /**
     * Test header.
     */
    public function testHeader()
    {
        $check = md5(microtime(true));
        $checkHeaders = ['something' => $check];
        $response = new ConnectorResponse($check, $checkHeaders, 200);
        $this->assertEquals($checkHeaders, $response->headers);
    }

    /**
     * Test http code.
     */
    public function testHttpCode()
    {
        $check = mt_rand(100, 500);
        $response = new ConnectorResponse(null, [], $check);
        $this->assertEquals($check, $response->httpCode);
    }
}
