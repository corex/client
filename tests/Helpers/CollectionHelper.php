<?php

namespace Tests\CoRex\Client\Helpers;

use CoRex\Client\Rest\Collection;

class CollectionHelper extends Collection
{
    /**
     * Current.
     *
     * @return EntityHelper
     */
    public function current()
    {
        return new EntityHelper(parent::current());
    }
}