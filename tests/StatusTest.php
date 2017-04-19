<?php

use CoRex\Client\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    /**
     * Test messages.
     */
    public function testMessages()
    {
        $messages = Status::getMessages();
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
        $this->assertEquals('', Status::getMessage(200));
        $this->assertEquals('Bad Request', Status::getMessage(400));
        $this->assertEquals('Unauthorized', Status::getMessage(401));
        $this->assertEquals('Not Found', Status::getMessage(404));
    }
}
