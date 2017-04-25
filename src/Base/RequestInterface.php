<?php

namespace CoRex\Client\Base;

interface RequestInterface
{
    /**
     * Set path.
     *
     * @param string $path
     */
    public function path($path);

    /**
     * Set token.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function token($name, $value);

    /**
     * Set query parameter.
     *
     * @param string $name
     * @param string $value Will be urlencoded automatically.
     * @return $this
     */
    public function param($name, $value);

    /**
     * Set header.
     *
     * @param string $header
     * @param string $value
     * @return $this
     */
    public function header($header, $value);
}