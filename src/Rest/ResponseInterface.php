<?php

namespace CoRex\Client\Rest;

interface ResponseInterface
{
    /**
     * Get.
     *
     * @param string $path Use dot notation. Default '' which means all.
     * @param mixed $defaultValue Default null.
     * @return mixed|null
     */
    public function get($path = '', $defaultValue = null);

    /**
     * Get integer.
     *
     * @param string $path Uses dot notation.
     * @param integer $defaultValue Default 0.
     * @return integer
     */
    public function getInteger($path, $defaultValue = 0);

    /**
     * Get boolean.
     *
     * @param string $path Uses dot notation.
     * @param boolean $defaultValue Default false.
     * @return boolean
     */
    public function getBoolean($path, $defaultValue = false);
}