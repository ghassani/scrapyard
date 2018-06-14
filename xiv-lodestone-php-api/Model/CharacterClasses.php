<?php
/**
 * Created by PhpStorm.
 * User: Gassan
 * Date: 9/13/14
 * Time: 10:10 PM
 */

namespace Spliced\Lodestone\Model;


class CharacterClasses {

    public $classes = array();

    /**
     * @param mixed $classes
     */
    public function setClasses(array $classes)
    {
        $this->classes = $classes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClasses()
    {
        return $this->classes;
    }

    public function addClass(CharacterClass $class)
    {
        $this->classes[$class->getName()] = $class;
        return $this;
    }

    public function getClass($name)
    {
        if (!isset($this->classes[$name])) {
           return false;
        }

        return $this->classes[$name];
    }

} 