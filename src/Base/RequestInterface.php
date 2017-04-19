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
     * Set header.
     *
     * @param string $header
     * @param string $value
     * @return $this
     */
    public function header($header, $value);
}