<?php

namespace Tests\CoRex\Client\Http;

use CoRex\Client\Http\Response;
use PHPUnit\Framework\TestCase;

class HttpResponseTest extends TestCase
{
    /**
     * Test constructor response.
     */
    public function testConstructorResponse()
    {
        $check = md5(microtime(true));
        $response = new Response($check, [], 200);
        $this->assertEquals($check, $response->body());
    }

    /**
     * Test header default value.
     */
    public function testHeaderDefaultValue()
    {
        $response = new Response(null, [], 200);
        $this->assertEquals('something', $response->header('test', 'something'));
    }

    /**
     * Test header.
     */
    public function testHeader()
    {
        $check = md5(microtime(true));
        $response = new Response($check, [
            'something' => $check
        ], 200);
        $this->assertEquals($check, $response->header('something'));
    }

    /**
     * Test status.
     */
    public function testStatus()
    {
        $check = mt_rand(100, 500);
        $response = new Response(null, [], $check);
        $this->assertEquals($check, $response->status());
    }
}
