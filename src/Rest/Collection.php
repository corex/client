<?php

namespace CoRex\Client\Rest;

use CoRex\Support\Collection as SupportCollection;

class Collection extends SupportCollection
{
    /**
     * Collection constructor.
     *
     * @param mixed $items Default null.
     */
    public function __construct($items = null)
    {
        if ($items instanceof Response) {
            $items = $items->toArray();
        }
        parent::__construct($items);
    }

    /**
     * To array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->values();
    }
}