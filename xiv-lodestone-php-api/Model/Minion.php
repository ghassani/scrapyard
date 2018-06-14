<?php
/**
 * Created by PhpStorm.
 * User: Gassan
 * Date: 9/14/14
 * Time: 1:59 PM
 */

namespace Spliced\Lodestone\Model;


class Minion {

    public $name;

    public $thumbnail;

    public function __construct($name = null, $thumbnail = null)
    {
        $this->name = $name;
        $this->thumbnail = $thumbnail;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }


} 