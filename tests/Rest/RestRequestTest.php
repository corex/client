<?php

use CoRex\Client\Base\Request as BaseRequest;
use CoRex\Client\Method;
use CoRex\Client\Rest\Request;
use CoRex\Support\Obj;
use PHPUnit\Framework\TestCase;

class RestRequestTest extends TestCase
{
    /**
     * Test constructor.
     */
    public function testConstructor()
    {
        $request = new Request(Method::OPTIONS);
        $this->assertEquals(Method::OPTIONS, $this->getRequestProperty($request, 'method'));
    }

    /**
     * Test constructor path set.
     */
    public function testConstructorPathSet()
    {
        $check = md5(microtime(true));
        $request = new Request(Method::OPTIONS, $check);
        $this->assertEquals($check, $this->getRequestProperty($request, 'path'));
    }

    /**
     * Test method not supported.
     */
    public function testMethodNotSupported()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Method unknown is not supported.');
        new Request('unknown');
    }

    /**
     * Test path.
     */
    public function testPath()
    {
        $check = md5(microtime(true));
        $request = new Request(Method::PUT);
        $request->path($check);
        $this->assertEquals($check, $this->getRequestProperty($request, 'path'));
    }

    /**
     * Test token.
     */
    public function testToken()
    {
        $check = md5(microtime(true));
        $request = new Request(Method::PUT);
        $request->token('token', $check);
        $this->assertEquals(['token' => $check], $this->getRequestProperty($request, 'tokens'));
    }

    /**
     * Test param.
     */
    public function testParam()
    {
        $check = md5(microtime(true));
        $request = new Request(Method::PUT);
        $request->param('param', $check);
        $this->assertEquals(['param' => $check], $this->getRequestProperty($request, 'parameters'));
    }

    /**
     * Test header.
     */
    public function testHeader()
    {
        $check = md5(microtime(true));
        $request = new Request(Method::PUT);
        $request->header('header', $check);
        $checkHeaders = [
            'header' => $check,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
        $this->assertEquals($checkHeaders, $this->getRequestProperty($request, 'headers'));
    }

    /**
     * Get request property.
     *
     * @param object $request
     * @param string $property
     * @return mixed
     */
    private function getRequestProperty($request, $property)
    {
        $properties = Obj::getPropertiesFromObject(Obj::PROPERTY_PRIVATE, $request, BaseRequest::class);
        if (isset($properties[$property])) {
            return $properties[$property];
        }
        return null;
    }
}
