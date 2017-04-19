<?php

namespace CoRex\Client\Rest;

use CoRex\Client\Base\Response as BaseResponse;

class Response extends BaseResponse
{
    private $array;

    /**
     * Value.
     *
     * @param string $path
     * @param mixed $defaultValue Default null.
     * @return mixed
     */
    public function value($path, $defaultValue = null)
    {
        $this->initialize();
        return $this->getData($path, $defaultValue);
    }

    /**
     * To array.
     *
     * @return array
     */
    public function toArray()
    {
        $this->initialize();
        return $this->array;
    }

    /**
     * Get data.
     *
     * @param string $path
     * @param mixed $defaultValue Default null.
     * @return mixed
     * @throws \Exception
     */
    private function getData($path, $defaultValue = null)
    {
        $data = $this->array;
        if ((string)$path == '') {
            return $data;
        }
        $pathSegments = explode('.', $path);
        foreach ($pathSegments as $pathSegment) {
            if (!isset($data[$pathSegment])) {
                return $defaultValue;
            }
            $data = $data[$pathSegment];
        }
        return $data;
    }

    /**
     * Initialize.
     */
    private function initialize()
    {
        if ($this->array !== null) {
            return;
        }
        $this->array = json_decode($this->body(), true);
    }
}