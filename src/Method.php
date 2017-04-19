<?php

namespace CoRex\Client;

class Method
{
    const GET = 'get';
    const POST = 'post';
    const PUT = 'put';
    const DELETE = 'delete';
    const PATCH = 'patch';
    const OPTIONS = 'options';

    /**
     * Get methods.
     *
     * @return array
     */
    public static function getMethods()
    {
        $reflectionClass = new \ReflectionClass(__CLASS__);
        return array_values($reflectionClass->getConstants());
    }

    /**
     * Is supported.
     *
     * @param boolean $method
     * @return boolean
     */
    public static function isSupported($method)
    {
        $methods = self::getMethods();
        return in_array(strtolower($method), $methods);
    }
}