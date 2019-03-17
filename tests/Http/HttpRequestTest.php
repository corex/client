<?php

namespace Tests\CoRex\Client\Http;

use CoRex\Client\Base\Request as BaseRequest;
use CoRex\Client\Http\Request;
use CoRex\Support\Obj;
use PHPUnit\Framework\TestCase;

class HttpRequestTest extends TestCase
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
     */
    public function testHeader()
    {
        $check = md5(microtime(true));
        $request = new Request();
        $request->header('header', $check);
        $this->assertEquals(['header' => $check], $this->getRequestProperty($request, 'headers'));
    }

    /**
     * Test body.
     */
    public function testBody()
    {
        $check = md5(microtime(true));
        $request = new Request();
        $request->body($check);
        $this->assertEquals($check, $this->getRequestProperty($request, 'body'));
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
        $properties = Obj::getProperties($request, BaseRequest::class, Obj::PROPERTY_PRIVATE);
        if (isset($properties[$property])) {
            return $properties[$property];
        }
        return null;
    }
}
