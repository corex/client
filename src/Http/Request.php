<?php

namespace CoRex\Client\Http;

class Request implements RequestInterface
{
    private $properties;

    /**
     * Request constructor.
     *
     * @param array $field Default null.
     * @param array $query Default null.
     * @param array $path Default null.
     */
    public function __construct(array $field = null, array $query = null, array $path = null)
    {
        $this->properties = [];
        $this->properties['path'] = [];
        $this->properties['query'] = [];
        $this->properties['field'] = [];

        // Set path.
        if ($path !== null && is_array($path)) {
            $this->properties['path'] = $path;
        }

        // Set query.
        if ($query !== null && is_array($query)) {
            $this->properties['query'] = $query;
        }

        // Set request.
        if ($field !== null && is_array($field)) {
            $this->properties['field'] = $field;
        }
    }

    /**
     * Set.
     *
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $this->properties['field'][$name] = $value;
    }

    /**
     * Set integer.
     *
     * @param string $name
     * @param integer $value
     */
    public function setInteger($name, $value)
    {
        $this->set($name, intval($value));
    }

    /**
     * Set boolean.
     *
     * @param string $name
     * @param boolean $value
     */
    public function setBoolean($name, $value)
    {
        $this->set($name, (boolean)$value);
    }

    /**
     * Set array.
     *
     * @param array $array
     * @throws \Exception
     */
    public function setArray(array $array)
    {
        if (!is_array($array)) {
            throw new \Exception('Array not parsed.');
        }
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Get properties.
     *
     * @return array
     */
    public function getProperties()
    {
        $properties = $this->properties;

        // Parse public properties.
        $reflectionObject = new \ReflectionObject($this);
        $reflectionProperties = $reflectionObject->getDefaultProperties();
        if (count($reflectionProperties) > 0) {
            foreach ($reflectionProperties as $property => $value) {
                $properties['field'][$property] = $this->{$property};
            }
        }

        return $properties;
    }
}