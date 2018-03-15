<?php

namespace CoRex\Client\Rest;

abstract class Entity
{
    /**
     * Constructor.
     *
     * @param mixed $data
     * @throws \ReflectionException
     */
    public function __construct($data)
    {
        if (!is_array($data)) {
            return;
        }
        $reflectionClass = new \ReflectionClass(get_class($this));
        $properties = $reflectionClass->getProperties();
        if (count($properties) > 0) {
            foreach ($properties as $property) {
                $property->setAccessible(true);
                if (isset($data[$property->name])) {
                    $property->setValue($this, $data[$property->name]);
                }
                if ($property->isPrivate() || $property->isProtected()) {
                    $property->setAccessible(false);
                }
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
}