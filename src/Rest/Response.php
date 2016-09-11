<?php

namespace CoRex\Client\Rest;

class Response implements ResponseInterface
{
    private $data;

    /**
     * Response constructor.
     *
     * @param array|string $response
     * @throws Exception
     */
    public function __construct($response)
    {
        if ($response === null) {
            return;
        }
        if (is_string($response) && in_array(substr($response, 0, 1), ['[', '{'])) {
            $response = json_decode($response, true);
        }
        if (!is_array($response)) {
            throw new Exception('Response specified is not an array or json.');
        }
        $this->data = $response;
    }

    /**
     * Get.
     *
     * @param string $path Use dot notation. Default '' which means all.
     * @param mixed $defaultValue Default null.
     * @return mixed|null
     */
    public function get($path = '', $defaultValue = null)
    {
        $data = &$this->data;
        if ($path != '') {
            $path = explode('.', $path);
            foreach ($path as $step) {
                if (isset($data[$step])) {
                    $data = &$data[$step];
                } else {
                    $data = $defaultValue;
                }
            }
        }
        return $data;
    }

    /**
     * Get integer.
     *
     * @param string $path Uses dot notation.
     * @param integer $defaultValue Default 0.
     * @return integer
     */
    public function getInteger($path, $defaultValue = 0)
    {
        return intval($this->get($path, $defaultValue));
    }

    /**
     * Get boolean.
     *
     * @param string $path Uses dot notation.
     * @param boolean $defaultValue Default false.
     * @return boolean
     */
    public function getBoolean($path, $defaultValue = false)
    {
        return (boolean)$this->get($path, $defaultValue);
    }
}