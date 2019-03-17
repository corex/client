<?php

namespace Tests\CoRex\Client\Rest;

use CoRex\Client\Rest\Response;
use PHPUnit\Framework\TestCase;

class RestResponseTest extends TestCase
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

    /**
     * Test value.
     */
    public function testValue()
    {
        $checkData = [
            'actor' => [
                'firstname' => 'Roger',
                'lastname' => 'Moore'
            ]
        ];
        $response = new Response(json_encode($checkData), [], 200);
        $this->assertEquals($checkData['actor']['firstname'], $response->value('actor.firstname'));
    }

    /**
     * Test value default null.
     */
    public function testValueDefaultNull()
    {
        $checkData = [
            'actor' => [
                'firstname' => 'Roger',
                'lastname' => 'Moore'
            ]
        ];
        $response = new Response(json_encode($checkData), [], 200);
        $this->assertNull($response->value('actor.unknown'));
    }

    /**
     * Test value default specified.
     */
    public function testValueDefaultSpecified()
    {
        $check = mt_rand(100, 500);
        $checkData = [
            'actor' => [
                'firstname' => 'Roger',
                'lastname' => 'Moore'
            ]
        ];
        $response = new Response(json_encode($checkData), [], 200);
        $this->assertEquals($check, $response->value('actor.unknown', $check));
    }

    /**
     * Test to array.
     */
    public function testToArray()
    {
        $checkData = [
            'actor' => [
                'firstname' => 'Roger',
                'lastname' => 'Moore'
            ]
        ];
        $response = new Response(json_encode($checkData), [], 200);
        $this->assertEquals($checkData, $response->toArray());
    }
}
