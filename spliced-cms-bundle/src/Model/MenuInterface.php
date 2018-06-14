<?php

namespace Spliced\Bundle\CmsBundle\Model;

interface MenuInterface
{


    /**
     * toArray
     *
     * Turn the object into an array, recursively for relations
     *
     * @return array
     */
    public function toArray();

    /**
     * toJson
     *
     * Turn the object into a valid JSON string
     *
     * Ideally, encode toArray()
     *
     * @return string
     */
    public function toJson();
}