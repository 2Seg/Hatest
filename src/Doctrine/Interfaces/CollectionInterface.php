<?php

namespace Hatest\Doctrine\Interfaces;

interface CollectionInterface
{
    /**
     * Initialize and return object
     *
     * @return mixed
     */
    public function init();

    /**
     * Return list of array with parameters attribute name and value to give.
     *
     * @return array
     */
    public function providerCollection();

}
