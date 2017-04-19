<?php

use CoRex\Client\Method;
use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{
    /**
     * Test get methods.
     */
    public function testGetMethods()
    {
        $this->assertEquals(
            ['get', 'post', 'put', 'delete', 'patch', 'options'],
            Method::getMethods()
        );
    }

    /**
     * Test is supported method wrong.
     */
    public function testIsSupportedMethodWrong()
    {
        $this->assertFalse(Method::isSupported('unknown'));
    }

    /**
     * Test is supported method get.
     */
    public function testIsSupportedMethodGet()
    {
        $this->assertTrue(Method::isSupported(Method::GET));
    }

    /**
     * Test is supported method post.
     */
    public function testIsSupportedMethodPost()
    {
        $this->assertTrue(Method::isSupported(Method::POST));
    }

    /**
     * Test is supported method put.
     */
    public function testIsSupportedMethodPut()
    {
        $this->assertTrue(Method::isSupported(Method::PUT));
    }

    /**
     * Test is supported method delete.
     */
    public function testIsSupportedMethodDelete()
    {
        $this->assertTrue(Method::isSupported(Method::DELETE));
    }

    /**
     * Test is supported method patch.
     */
    public function testIsSupportedMethodPatch()
    {
        $this->assertTrue(Method::isSupported(Method::PATCH));
    }

    /**
     * Test is supported method options.
     */
    public function testIsSupportedMethodOptions()
    {
        $this->assertTrue(Method::isSupported(Method::OPTIONS));
    }
}
