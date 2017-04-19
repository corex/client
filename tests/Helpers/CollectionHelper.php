<?php

use CoRex\Client\Rest\Collection;

class CollectionHelper extends Collection
{
    public function current()
    {
        return new EntityHelper(parent::current());
    }
}