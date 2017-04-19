<?php

namespace CoRex\Client\Rest;

abstract class Entity
{
    /**
     * Data constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $properties = $this->getProperties();
        if (count($properties) > 0) {
            foreach ($properties as $property) {
                $value = null;
                if (isset($data[$property])) {
                    $value = $data[$property];
                }
                $this->{$property} = $value;
            }
        }
    }

    /**
     * To array.
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * Get properties.
     *
     * @return array
     */
    private function getProperties()
    {
        $reflectionClass = new \ReflectionClass(get_class($this));
        $properties = $reflectionClass->getProperties();
        $result = [];
        if (count($properties) > 0) {
            foreach ($properties as $property) {
                $result[] = $property->name;
            }
        }
        return $result;
    }
}