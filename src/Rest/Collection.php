<?php

namespace CoRex\Client\Rest;

abstract class Collection implements \Iterator, \Countable
{
    private $data;

    /**
     * Data constructor.
     *
     * @param mixed $data
     */
    public function __construct($data)
    {
        if ($data instanceof Response) {
            $data = $data->toArray();
        }
        $this->data = $data;
    }

    /**
     * To array.
     *
     * @return array
     */
    public function toArray()
    {
        if ($this->data === null) {
            return [];
        }
        return $this->data;
    }

    /**
     * Get count.
     *
     * @return integer
     */
    public function count()
    {
        if ($this->data !== null) {
            return count($this->data);
        }
        return 0;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        if ($this->data === null) {
            return null;
        }
        return current($this->data);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        if ($this->data === null) {
            return null;
        }
        next($this->data);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        if ($this->data === null) {
            return null;
        }
        return key($this->data);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        if ($this->data === null) {
            return false;
        }
        $key = key($this->data);
        return ($key !== null && $key !== false);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        if ($this->data === null) {
            return null;
        }
        reset($this->data);
    }
}