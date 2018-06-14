<?php
/**
 * Created by PhpStorm.
 * User: Gassan
 * Date: 9/13/14
 * Time: 10:08 PM
 */

namespace Spliced\Lodestone\Model;


class CharacterEquipmentItem extends Item
{
    public $craftedBy;

    public $glamoured;

    public $glamouredItemName;

    public $glamouredItemId;

    /**
     * @param mixed $craftedBy
     */
    public function setCraftedBy($craftedBy)
    {
        $this->craftedBy = $craftedBy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCraftedBy()
    {
        return $this->craftedBy;
    }

    /**
     * @param mixed $glamoured
     */
    public function setGlamoured($glamoured)
    {
        $this->glamoured = $glamoured;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGlamoured()
    {
        return $this->glamoured;
    }

    /**
     * @param mixed $glamouredItemId
     */
    public function setGlamouredItemId($glamouredItemId)
    {
        $this->glamouredItemId = $glamouredItemId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGlamouredItemId()
    {
        return $this->glamouredItemId;
    }

    /**
     * @param mixed $glamouredItemName
     */
    public function setGlamouredItemName($glamouredItemName)
    {
        $this->glamouredItemName = $glamouredItemName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGlamouredItemName()
    {
        return $this->glamouredItemName;
    }


}