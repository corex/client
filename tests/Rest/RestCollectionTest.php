<?php

use PHPUnit\Framework\TestCase;

class RestCollectionTest extends TestCase
{
    /**
     * Test count.
     */
    public function testCount()
    {
        $testData = $this->getTestData();
        $collection = new CollectionHelper($testData);
        $this->assertEquals(count($testData), $collection->count());
    }

    /**
     * Test to array.
     */
    public function testToArray()
    {
        $testData = $this->getTestData();
        $collection = new CollectionHelper($testData);
        $this->assertEquals($testData, $collection->toArray());
    }

    /**
     * Test for each.
     */
    public function testForEarch()
    {
        $testData = $this->getTestData();
        $collection = new CollectionHelper($testData);
        $index = 0;
        foreach ($collection as $item) {
            $this->assertEquals($testData[$index]['firstname'], $item->firstname);
            $this->assertEquals($testData[$index]['lastname'], $item->lastname);
            $index++;
        }
    }

    /**
     * Get test data.
     *
     * @return array
     */
    private function getTestData()
    {
        return [
            [
                'firstname' => 'Roger',
                'lastname' => 'Moore'
            ],
            [
                'firstname' => 'Sean',
                'lastname' => 'Connery'
            ]
        ];
    }
}
