<?php

namespace Tests\CoRex\Client\Rest;

use CoRex\Client\Base\Request as BaseRequest;
use CoRex\Client\Rest\Request;
use CoRex\Support\Obj;
use PHPUnit\Framework\TestCase;

class RestRequestTest extends TestCase
{
    /**
     * Test constructor path set.
     */
    public function testConstructorPathSet()
    {
        $check = md5(microtime(true));
        $request = new Request($check);
        $this->assertEquals($check, $this->getRequestProperty($request, 'path'));
    }

    /**
     * Test path.
     */
    public function testPath()
    {
        $check = md5(microtime(true));
        $request = new Request();
        $request->path($check);
        $this->assertEquals($check, $this->getRequestProperty($request, 'path'));
    }

    /**
     * Test token.
     */
    public function testToken()
    {
        $check = md5(microtime(true));
        $request = new Request();
        $request->token('token', $check);
        $this->assertEquals(['token' => $check], $this->getRequestProperty($request, 'tokens'));
    }

    /**
     * Test param.
     */
    public function testParam()
    {
        $check = md5(microtime(true));
        $request = new Request();
        $request->param('param', $check);
        $this->assertEquals(['param' => $check], $this->getRequestProperty($request, 'parameters'));
    }

    /**
     * Test header.
     * @throws \ReflectionException
     */
    public function testHeader()
    {
        $check = md5(microtime(true));
        $request = new Request();
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
     * @throws \ReflectionException
     */
    private function getRequestProperty($request, $property)
    {
        $properties = Obj::getProperties($request, BaseRequest::class, Obj::PROPERTY_PRIVATE);
        if (isset($properties[$property])) {
            return $properties[$property];
        }
        return null;
    }
}
