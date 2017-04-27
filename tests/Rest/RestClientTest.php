<?php

use CoRex\Client\Base\Client as BaseClient;
use CoRex\Client\Method;
use CoRex\Client\Rest\Client;
use CoRex\Client\Rest\Request;
use CoRex\Client\Rest\Response;
use CoRex\Support\Obj;
use PHPUnit\Framework\TestCase;

class RestClientTest extends TestCase
{
    private $baseUrl = 'http://this.is.a.test/{token}/test';

    /**
     * Test base url constructor.
     */
    public function testBaseUrlConstructor()
    {
        $client = new Client($this->baseUrl);
        $properties = Obj::getPropertiesFromObject(Obj::PROPERTY_PRIVATE, $client, BaseClient::class);
        $this->assertEquals($this->baseUrl, $properties['baseUrl']);
    }

    /**
     * Test base url.
     */
    public function testBaseUrl()
    {
        $client = new Client();
        $client->baseUrl($this->baseUrl);
        $properties = Obj::getPropertiesFromObject(Obj::PROPERTY_PRIVATE, $client, BaseClient::class);
        $this->assertEquals($this->baseUrl, $properties['baseUrl']);
    }

    /**
     * Test token request.
     */
    public function testTokenRequest()
    {
        $check = md5(microtime(true));
        $request = new Request();
        $request->token('token', $check);

        $client = $this->getTestClient([], [], 0);
        $client->call(Method::GET, $request);

        $debug = $client->getDebug();
        $this->assertEquals(['token' => $check], $debug['tokens']);
    }

    /**
     * Test token client.
     */
    public function testTokenClient()
    {
        $check = md5(microtime(true));
        $request = new Request();

        $client = $this->getTestClient([], [], 0);
        $client->token('token', $check);
        $client->call(Method::GET, $request);

        $debug = $client->getDebug();
        $this->assertEquals(['token' => $check], $debug['tokens']);
    }

    /**
     * Test token is final.
     */
    public function testTokenIsFinal()
    {
        $check1 = md5(microtime(true)) . '_1';
        $check2 = md5(microtime(true)) . '_2';
        $request = new Request();
        $request->token('token', $check1);

        $client = $this->getTestClient([], [], 0);
        $client->token('token', $check2, true);
        $client->call(Method::GET, $request);

        $debug = $client->getDebug();
        $this->assertEquals(['token' => $check2], $debug['tokens']);
    }

    /**
     * Test param request.
     */
    public function testParamRequest()
    {
        $check = md5(microtime(true));
        $request = new Request();
        $request->param('param', $check);

        $client = $this->getTestClient([], [], 0);
        $client->call(Method::GET, $request);

        $debug = $client->getDebug();
        $this->assertEquals(['param' => $check], $debug['parameters']);
    }

    /**
     * Test param client.
     */
    public function testParamClient()
    {
        $check = md5(microtime(true));
        $request = new Request();

        $client = $this->getTestClient([], [], 0);
        $client->param('param', $check);
        $client->call(Method::GET, $request);

        $debug = $client->getDebug();
        $this->assertEquals(['param' => $check], $debug['parameters']);
    }

    /**
     * Test param is final.
     */
    public function testParamIsFinal()
    {
        $check1 = md5(microtime(true)) . '_1';
        $check2 = md5(microtime(true)) . '_2';
        $request = new Request();
        $request->param('param', $check1);

        $client = $this->getTestClient([], [], 0);
        $client->param('param', $check2, true);
        $client->call(Method::GET, $request);

        $debug = $client->getDebug();
        $this->assertEquals(['param' => $check2], $debug['parameters']);
    }

    /**
     * Test header request.
     */
    public function testHeaderRequest()
    {
        $check = md5(microtime(true));
        $request = new Request();
        $request->header('header', $check);

        $client = $this->getTestClient([], [], 0);
        $client->call(Method::GET, $request);

        $debug = $client->getDebug();
        $this->assertEquals([
            'header' => $check,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ], $debug['headers']);
    }

    /**
     * Test header client.
     */
    public function testHeaderClient()
    {
        $check = md5(microtime(true));
        $request = new Request();

        $client = $this->getTestClient([], [], 0);
        $client->header('header', $check);
        $client->call(Method::GET, $request);

        $debug = $client->getDebug();
        $this->assertEquals([
            'header' => $check,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ], $debug['headers']);
    }

    /**
     * Test header is final.
     */
    public function testHeaderIsFinal()
    {
        $check1 = md5(microtime(true)) . '_1';
        $check2 = md5(microtime(true)) . '_2';
        $request = new Request();
        $request->header('header', $check1);

        $client = $this->getTestClient([], [], 0);
        $client->header('header', $check2, true);
        $client->call(Method::GET, $request);

        $debug = $client->getDebug();
        $this->assertEquals([
            'header' => $check2,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ], $debug['headers']);
    }

    /**
     * Test user agent.
     */
    public function testUserAgent()
    {
        $userAgent = 'user.agent';

        $request = new Request();

        $client = $this->getTestClient([], [], 0);
        $client->userAgent($userAgent);
        $client->call(Method::GET, $request);

        $debug = $client->getDebug();
        $this->assertEquals($userAgent, $debug['userAgent']);
    }

    /**
     * Test call wrong.
     */
    public function testCallWrong()
    {
        $this->expectException(TypeError::class);
        $request = new stdClass();
        $client = $this->getTestClient([], [], 0);
        $client->call(Method::GET, $request);
    }

    /**
     * Test call correct.
     */
    public function testCallCorrect()
    {
        $request = new Request();
        $client = $this->getTestClient([], [], 0);
        $response = $client->call(Method::GET, $request);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * Test no request.
     */
    public function testNoRequest()
    {
        $method = Method::GET;
        $client = $this->getTestClient([], [], 0);
        $client->call($method);
        $debug = $client->getDebug();
        $this->assertEquals($method, $debug['method']);
        $this->assertEquals($this->baseUrl, $debug['url']);
        $this->assertEquals('', $debug['userAgent']);
        $this->assertEquals([], $debug['tokens']);
        $this->assertEquals([], $debug['parameters']);
        $this->assertEquals([], $debug['headers']);
        $this->assertNull($debug['body']);
        $this->assertEquals(0, $debug['status']);
    }

    /**
     * Test call method get.
     */
    public function testCallMethodGet()
    {
        $method = Method::GET;
        $client = $this->getTestClient([], [], 0);
        $client->call($method);
        $properties = $client->getDebug();
        $this->assertEquals($method, $properties['method']);
    }

    /**
     * Test call method post.
     */
    public function testCallMethodPost()
    {
        $method = Method::POST;
        $client = $this->getTestClient([], [], 0);
        $client->call($method);
        $properties = $client->getDebug();
        $this->assertEquals($method, $properties['method']);
    }

    /**
     * Test call method put.
     */
    public function testCallMethodPut()
    {
        $method = Method::PUT;
        $client = $this->getTestClient([], [], 0);
        $client->call($method);
        $properties = $client->getDebug();
        $this->assertEquals($method, $properties['method']);
    }

    /**
     * Test call method delete.
     */
    public function testCallMethodDelete()
    {
        $method = Method::DELETE;
        $client = $this->getTestClient([], [], 0);
        $client->call($method);
        $properties = $client->getDebug();
        $this->assertEquals($method, $properties['method']);
    }

    /**
     * Test call method patch.
     */
    public function testCallMethodPatch()
    {
        $method = Method::PATCH;
        $client = $this->getTestClient([], [], 0);
        $client->call($method);
        $properties = $client->getDebug();
        $this->assertEquals($method, $properties['method']);
    }

    /**
     * Test call method options.
     */
    public function testCallMethodOptions()
    {
        $method = Method::OPTIONS;
        $client = $this->getTestClient([], [], 0);
        $client->call($method);
        $properties = $client->getDebug();
        $this->assertEquals($method, $properties['method']);
    }

    /**
     * Test call method unknown.
     */
    public function testCallMethodUnknown()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Method unknown is not supported');
        $method = 'unknown';
        $client = $this->getTestClient([], [], 0);
        $client->call($method);
    }

    /**
     * Get test client.
     *
     * @param mixed $testResponse
     * @param array $testHeaders
     * @param integer $testStatus
     * @return Client
     * @throws Exception
     */
    private function getTestClient($testResponse, array $testHeaders, $testStatus)
    {
        if ($testResponse === null) {
            throw new Exception('You must specify a test response.');
        }
        if (is_array($testResponse)) {
            $testResponse = json_encode($testResponse);
        }
        $client = new Client();
        $client->baseUrl($this->baseUrl);

        // Set test properties.
        $testProperties = [
            'testResponse' => $testResponse,
            'testHeaders' => $testHeaders,
            'testStatus' => $testStatus,
        ];
        Obj::setProperties($client, $testProperties, BaseClient::class);

        return $client;
    }
}
