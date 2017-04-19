<?php

use CoRex\Client\Base\Client as BaseClient;
use CoRex\Client\Connector\CurlConnector;
use CoRex\Client\Http\Request;
use CoRex\Client\Method;
use CoRex\Client\Rest\Client;
use CoRex\Client\Rest\Response;
use CoRex\Support\Obj;
use PHPUnit\Framework\TestCase;

class RestClientTest extends TestCase
{
    private $baseUrl = 'http://this.is.a.test/{token}/test';

    /**
     * Test client connector default.
     */
    public function testClientConnectorDefault()
    {
        $client = new Client();
        $properties = Obj::getPropertiesFromObject(Obj::PROPERTY_PRIVATE, $client, BaseClient::class);
        $this->assertTrue($properties['connector'] instanceof CurlConnector);
    }

    /**
     * Test client connector test.
     */
    public function testClientConnectorTest()
    {
        $testConnector = new TestConnector();
        $client = new Client($testConnector);
        $properties = Obj::getPropertiesFromObject(Obj::PROPERTY_PRIVATE, $client, BaseClient::class);
        $this->assertTrue($properties['connector'] instanceof TestConnector);
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
        $request = new Request(Method::GET);
        $request->token('token', $check);

        $client = $this->getTestClient();
        $client->call($request);

        $connector = PropertiesHelper::getClientConnector($client, BaseClient::class);
        $request = PropertiesHelper::getConnectorRequest($connector);

        $this->assertEquals(['token' => $check], $request->tokens);
    }

    /**
     * Test token client.
     */
    public function testTokenClient()
    {
        $check = md5(microtime(true));
        $request = new Request(Method::GET);

        $client = $this->getTestClient();
        $client->token('token', $check);
        $client->call($request);

        $connector = PropertiesHelper::getClientConnector($client, BaseClient::class);
        $request = PropertiesHelper::getConnectorRequest($connector);

        $this->assertEquals(['token' => $check], $request->tokens);
    }

    /**
     * Test token is final.
     */
    public function testTokenIsFinal()
    {
        $check1 = md5(microtime(true)) . '_1';
        $check2 = md5(microtime(true)) . '_2';
        $request = new Request(Method::GET);
        $request->token('token', $check1);

        $client = $this->getTestClient();
        $client->token('token', $check2, true);
        $client->call($request);

        $connector = PropertiesHelper::getClientConnector($client, BaseClient::class);
        $request = PropertiesHelper::getConnectorRequest($connector);

        $this->assertEquals(['token' => $check2], $request->tokens);
    }

    /**
     * Test param request.
     */
    public function testParamRequest()
    {
        $check = md5(microtime(true));
        $request = new Request(Method::GET);
        $request->param('param', $check);

        $client = $this->getTestClient();
        $client->call($request);

        $connector = PropertiesHelper::getClientConnector($client, BaseClient::class);
        $request = PropertiesHelper::getConnectorRequest($connector);

        $this->assertEquals(['param' => $check], $request->parameters);
    }

    /**
     * Test param client.
     */
    public function testParamClient()
    {
        $check = md5(microtime(true));
        $request = new Request(Method::GET);

        $client = $this->getTestClient();
        $client->param('param', $check);
        $client->call($request);

        $connector = PropertiesHelper::getClientConnector($client, BaseClient::class);
        $request = PropertiesHelper::getConnectorRequest($connector);

        $this->assertEquals(['param' => $check], $request->parameters);
    }

    /**
     * Test param is final.
     */
    public function testParamIsFinal()
    {
        $check1 = md5(microtime(true)) . '_1';
        $check2 = md5(microtime(true)) . '_2';
        $request = new Request(Method::GET);
        $request->param('param', $check1);

        $client = $this->getTestClient();
        $client->param('param', $check2, true);
        $client->call($request);

        $connector = PropertiesHelper::getClientConnector($client, BaseClient::class);
        $request = PropertiesHelper::getConnectorRequest($connector);
        $this->assertEquals(['param' => $check2], $request->parameters);
    }

    /**
     * Test header request.
     */
    public function testHeaderRequest()
    {
        $check = md5(microtime(true));
        $request = new Request(Method::GET);
        $request->header('header', $check);

        $client = $this->getTestClient();
        $client->call($request);

        $connector = PropertiesHelper::getClientConnector($client, BaseClient::class);
        $request = PropertiesHelper::getConnectorRequest($connector);

        $this->assertEquals(['header' => $check], $request->headers);
    }

    /**
     * Test header client.
     */
    public function testHeaderClient()
    {
        $check = md5(microtime(true));
        $request = new Request(Method::GET);

        $client = $this->getTestClient();
        $client->header('header', $check);
        $client->call($request);

        $connector = PropertiesHelper::getClientConnector($client, BaseClient::class);
        $request = PropertiesHelper::getConnectorRequest($connector);

        $this->assertEquals(['header' => $check], $request->headers);
    }

    /**
     * Test header is final.
     */
    public function testHeaderIsFinal()
    {
        $check1 = md5(microtime(true)) . '_1';
        $check2 = md5(microtime(true)) . '_2';
        $request = new Request(Method::GET);
        $request->header('header', $check1);

        $client = $this->getTestClient();
        $client->header('header', $check2, true);
        $client->call($request);

        $connector = PropertiesHelper::getClientConnector($client, BaseClient::class);
        $request = PropertiesHelper::getConnectorRequest($connector);
        $this->assertEquals(['header' => $check2], $request->headers);
    }

    /**
     * Test user agent.
     */
    public function testUserAgent()
    {
        $userAgent = 'user.agent';

        $request = new Request(Method::GET);

        $client = $this->getTestClient();
        $client->userAgent($userAgent);
        $client->call($request);

        $properties = Obj::getPropertiesFromObject(Obj::PROPERTY_PRIVATE, $client, BaseClient::class);
        $this->assertEquals($userAgent, $properties['userAgent']);
    }

    /**
     * Test get connector.
     */
    public function testGetConnector()
    {
        $client = $this->getTestClient();

        $reflectionClass = new ReflectionClass($client);
        $method = $reflectionClass->getMethod('getConnector');
        $method->setAccessible(true);
        $getConnector = $method->getClosure($client);

        $connector = $getConnector();
        $this->assertInstanceOf(TestConnector::class, $connector);
    }

    /**
     * Test call wrong.
     */
    public function testCallWrong()
    {
        $this->expectException(TypeError::class);
        $request = new stdClass();
        $client = $this->getTestClient();
        $client->call($request);
    }

    /**
     * Test call correct.
     */
    public function testCallCorrect()
    {
        $request = new Request(Method::GET);
        $client = $this->getTestClient();
        $response = $client->call($request);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * Get test client.
     *
     * @return Client
     */
    private function getTestClient()
    {
        $connector = $this->getTestConnector();
        $client = new Client($connector);
        $client->baseUrl($this->baseUrl);
        return $client;
    }

    /**
     * Get test connector.
     *
     * @return TestConnector
     */
    private function getTestConnector()
    {
        $connector = new TestConnector();
        return $connector;
    }
}
