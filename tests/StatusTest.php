<?php

namespace Tests\CoRex\Client;

use CoRex\Client\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    /**
     * Test messages.
     */
    public function testMessages()
    {
        $messages = Status::messages();
        $this->assertEquals('OK', $messages['200']);
        $this->assertEquals('Bad Request', $messages['400']);
        $this->assertEquals('Unauthorized', $messages['401']);
        $this->assertEquals('Not Found', $messages['404']);
    }

    /**
     * Test get message.
     */
    public function testGetMessage()
    {
        $this->assertEquals('', Status::message(200));
        $this->assertEquals('Bad Request', Status::message(400));
        $this->assertEquals('Unauthorized', Status::message(401));
        $this->assertEquals('Not Found', Status::message(404));
    }
}
