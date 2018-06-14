<?php

namespace Spliced\Lodestone\Model;

/**
 * Class CharacterSearchResult
 *
 * @package Spliced\Lodestone
 */
class CharacterSearchResult {
    
    public $id;
    
    public $name;
    
    public $world;

    public $currentClass;

    public $currentClassImage;
    
    public $currentClassLevel;
    
    public $grandCompany;
    
    public $grandCompanyRank;

    public $freeCompany;

    public $freeCompanyRank;
    
    public $languages = array();
    
    public $thumbnail;

    /**
     * @param mixed $currentClass
     */
    public function setCurrentClass($currentClass)
    {
        $this->currentClass = $currentClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentClass()
    {
        return $this->currentClass;
    }

    /**
     * @param mixed $currentClassLevel
     */
    public function setCurrentClassLevel($currentClassLevel)
    {
        $this->currentClassLevel = $currentClassLevel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentClassLevel()
    {
        return $this->currentClassLevel;
    }

    /**
     * @param mixed $grandCompany
     */
    public function setGrandCompany($grandCompany)
    {
        $this->grandCompany = $grandCompany;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGrandCompany()
    {
        return $this->grandCompany;
    }

    /**
     * @param mixed $grandCompanyRank
     */
    public function setGrandCompanyRank($grandCompanyRank)
    {
        $this->grandCompanyRank = $grandCompanyRank;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGrandCompanyRank()
    {
        return $this->grandCompanyRank;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param array $languages
     */
    public function setLanguages(array $languages = array())
    {
        $this->languages = $languages;
        return $this;
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
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

    /**
     * @param mixed $world
     */
    public function setWorld($world)
    {
        $this->world = $world;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWorld()
    {
        return $this->world;
    }

    /**
     * @param mixed $freeCompany
     */
    public function setFreeCompany($freeCompany)
    {
        $this->freeCompany = $freeCompany;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFreeCompany()
    {
        return $this->freeCompany;
    }

    /**
     * @param mixed $freeCompanyRank
     */
    public function setFreeCompanyRank($freeCompanyRank)
    {
        $this->freeCompanyRank = $freeCompanyRank;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFreeCompanyRank()
    {
        return $this->freeCompanyRank;
    }

    /**
     * @param mixed $currentClassImage
     */
    public function setCurrentClassImage($currentClassImage)
    {
        $this->currentClassImage = $currentClassImage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentClassImage()
    {
        return $this->currentClassImage;
    }


    
} 