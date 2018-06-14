<?php
/**
 * Created by PhpStorm.
 * User: Gassan
 * Date: 9/15/14
 * Time: 9:41 PM
 */

namespace Spliced\Lodestone\Model;


class CharacterClass {

    public $name;

    public $level;

    public $progress;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
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
     * @param mixed $progress
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProgress()
    {
        return $this->progress;
    }


} 