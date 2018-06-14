<?php
/**
 * Created by PhpStorm.
 * User: Gassan
 * Date: 9/8/14
 * Time: 9:18 PM
 */

namespace Spliced\Lodestone\Model;


class Item {

    public $id;

    public $name;

    public $itemLevel;

    public $requiredLevel;

    public $classRequirements = array();

    public $attributes = array(
        'defense' => 0,
        'magic_defense' => 0,
        'physical_damage' => 0,
        'auto-attack' => 0,
        'delay' => 0,
    );

    public $bonuses = array(
        'strength' => 0,
        'critical hit rate' => 0,
        'skill speed' => 0,
        'determination' => 0,
        'dexterity' => 0,
        'vitality' => 0,
        'accuracy' => 0,
        'mind' => 0,
        'piety' => 0,
        'intelligence' => 0,
        'gp' => 0,
        'cp' => 0,
        'gathering' => 0,
        'perception' => 0,
        'control' => 0,
        'craftsmanship' => 0,
        'parry' => 0,
        'healing_magic_potency' => 0,
        'attack_magic_porency' => 0,
        'fire' => 0,
        'ice' => 0,
        'wind' => 0,
        'earth' => 0,
        'lightning' => 0,
        'water' => 0,
    );

    public $repairLevel;

    public $repairClass;

    public $repairMaterials;

    public $thumbnail;

    public $mediumThumbnail;

    public $image;

    public $convertible = false;

    public $projectable = false;

    public $desynthesizable = false;

    public $sellable = false;

    public $unique = false;

    public $dyeable = false;

    public $crestsable = false;

    /**
     * @param array $classRequirements
     */
    public function setClassRequirements(array $classRequirements = array())
    {
        $this->classRequirements = $classRequirements;
        return $this;
    }

    /**
     * @return array
     */
    public function getClassRequirements()
    {
        return $this->classRequirements;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes = array())
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $bonuses
     */
    public function setBonuses(array $bonuses = array())
    {
        $this->bonuses = $bonuses;
        return $this;
    }

    /**
     * @return array
     */
    public function getBonuses()
    {
        return $this->bonuses;
    }

    public function setBonus($name, $value)
    {
        $this->bonuses[$name] = $value;
        return $this;
    }

    /**
     * @param boolean $crestsable
     */
    public function setCrestsable($crestsable)
    {
        $this->crestsable = $crestsable;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getCrestsable()
    {
        return $this->crestsable;
    }

    /**
     * @param boolean $convertible
     */
    public function setConvertible($convertible)
    {
        $this->convertible = $convertible;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getConvertible()
    {
        return $this->convertible;
    }

    /**
     * @param boolean $desynthesizable
     */
    public function setDesynthesizable($desynthesizable)
    {
        $this->desynthesizable = $desynthesizable;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getDesynthesizable()
    {
        return $this->desynthesizable;
    }

    /**
     * @param boolean $dyeable
     */
    public function setDyeable($dyeable)
    {
        $this->dyeable = $dyeable;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getDyeable()
    {
        return $this->dyeable;
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
     * @param mixed $itemLevel
     */
    public function setItemLevel($itemLevel)
    {
        $this->itemLevel = $itemLevel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItemLevel()
    {
        return $this->itemLevel;
    }

    /**
     * @param mixed $mediumThumbnail
     */
    public function setMediumThumbnail($mediumThumbnail)
    {
        $this->mediumThumbnail = $mediumThumbnail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMediumThumbnail()
    {
        return $this->mediumThumbnail;
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
     * @param boolean $projectable
     */
    public function setProjectable($projectable)
    {
        $this->projectable = $projectable;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getProjectable()
    {
        return $this->projectable;
    }

    /**
     * @param mixed $repairClass
     */
    public function setRepairClass($repairClass)
    {
        $this->repairClass = $repairClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRepairClass()
    {
        return $this->repairClass;
    }

    /**
     * @param mixed $repairLevel
     */
    public function setRepairLevel($repairLevel)
    {
        $this->repairLevel = $repairLevel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRepairLevel()
    {
        return $this->repairLevel;
    }

    /**
     * @param mixed $repairMaterials
     */
    public function setRepairMaterials($repairMaterials)
    {
        $this->repairMaterials = $repairMaterials;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRepairMaterials()
    {
        return $this->repairMaterials;
    }

    /**
     * @param mixed $requiredLevel
     */
    public function setRequiredLevel($requiredLevel)
    {
        $this->requiredLevel = $requiredLevel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequiredLevel()
    {
        return $this->requiredLevel;
    }

    /**
     * @param boolean $sellable
     */
    public function setSellable($sellable)
    {
        $this->sellable = $sellable;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getSellable()
    {
        return $this->sellable;
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
     * @param boolean $unique
     */
    public function setUnique($unique)
    {
        $this->unique = $unique;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getUnique()
    {
        return $this->unique;
    }


} 