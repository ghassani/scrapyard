<?php
/**
 * Created by PhpStorm.
 * User: Gassan
 * Date: 9/8/14
 * Time: 9:17 PM
 */

namespace Spliced\Lodestone\Model;


class Character {

    public $id;

    public $name;

    public $world;

    public $grandCompany;

    public $grandCompanyRank;

    public $freeCompany;

    public $freeCompanyRank;

    public $currentClass;

    public $currentClassLevel;

    public $race;

    public $tribe;

    public $gender;

    public $nameday;

    public $guardian;

    public $cityState;

    public $attributes;

    public $classes = array();

    public $mounts = array();

    public $minions = array();

    public $thumbnail;

    public $image;

    public $equipment;

    /**
     * @param CharacterAttributes|null $attributes
     */
    public function setAttributes(CharacterAttributes $attributes = null)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return CharacterAttributes|null
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param CharacterClasses|null $classes
     */
    public function setClasses(CharacterClasses $classes = null)
    {
        $this->classes = $classes;
        return $this;
    }

    /**
     * @return CharacterClasses
     */
    public function getClasses()
    {
        return $this->classes;
    }

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

    public function getMinions()
    {
        return $this->minions;
    }

    public function addMinion(Minion $minion)
    {
        $this->minions[] = $minion;
        return $this;
    }

    public function getMounts()
    {
        return $this->mounts;
    }

    public function addMount(Mount $mount)
    {
        $this->mounts[] = $mount;
        return $this;
    }

    /**
     * @param mixed $cityState
     */
    public function setCityState($cityState)
    {
        $this->cityState = $cityState;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCityState()
    {
        return $this->cityState;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
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
     * @param mixed $guardian
     */
    public function setGuardian($guardian)
    {
        $this->guardian = $guardian;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGuardian()
    {
        return $this->guardian;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $nameday
     */
    public function setNameday($nameday)
    {
        $this->nameday = $nameday;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNameday()
    {
        return $this->nameday;
    }

    /**
     * @param mixed $race
     */
    public function setRace($race)
    {
        $this->race = $race;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * @param mixed $thumbail
     */
    public function setThumbnail($thumbail)
    {
        $this->thumbnail = $thumbail;
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
     * @param mixed $tribe
     */
    public function setTribe($tribe)
    {
        $this->tribe = $tribe;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTribe()
    {
        return $this->tribe;
    }

    /**
     * @param mixed $equipment
     */
    public function setEquipment(CharacterEquipment $equipment = null)
    {
        $this->equipment = $equipment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEquipment()
    {
        return $this->equipment;
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


}