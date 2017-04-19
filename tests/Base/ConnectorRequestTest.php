<?php

use CoRex\Client\Base\ConnectorRequest;
use PHPUnit\Framework\TestCase;

class ConnectorRequestTest extends TestCase
{
    /**
     * Test method.
     */
    public function testMethod()
    {
        $request = new ConnectorRequest();
        $this->assertTrue(is_string($request->method));
        $this->assertEquals('', $request->method);
    }

    /**
     * Test url.
     */
    public function testUrl()
    {
        $request = new ConnectorRequest();
        $this->assertTrue(is_string($request->url));
        $this->assertEquals('', $request->url);
    }

    /**
     * Test tokens.
     */
    public function testTokens()
    {
        $request = new ConnectorRequest();
        $this->assertTrue(is_array($request->tokens));
        $this->assertEquals([], $request->tokens);
    }

    /**
     * Test parameters.
     */
    public function testParameters()
    {
        $request = new ConnectorRequest();
        $this->assertTrue(is_array($request->parameters));
        $this->assertEquals([], $request->parameters);
    }

    /**
     * Test headers.
     */
    public function testHeaders()
    {
        $request = new ConnectorRequest();
        $this->assertTrue(is_array($request->headers));
        $this->assertEquals([], $request->headers);
    }

    /**
     * Test body.
     */
    public function testBody()
    {
        $request = new ConnectorRequest();
        $this->assertNull($request->body);
    }

    /**
     * Test user agent.
     */
    public function testUserAgent()
    {
        $request = new ConnectorRequest();
        $this->assertTrue(is_string($request->method));
        $this->assertEquals('', $request->method);
    }
}
