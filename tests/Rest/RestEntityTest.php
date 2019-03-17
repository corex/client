<?php

namespace Tests\CoRex\Client\Rest;

use PHPUnit\Framework\TestCase;
use Tests\CoRex\Client\Helpers\EntityHelper;

class RestEntityTest extends TestCase
{
    /**
     * Test to array.
     */
    public function testToArray()
    {
        $check1 = mt_rand(100, 500);
        $firstname = 'Roger';
        $lastname = 'Moore';
        $checkData = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'check1' => $check1
        ];
        $entity = new EntityHelper($checkData);
        $this->assertEquals($firstname, $entity->firstname);
        $this->assertEquals($lastname, $entity->lastname);
        $this->assertEquals($check1, $entity->check1);
        $this->assertNull($entity->check2);
    }
}
