<?php
/**
 * Created by PhpStorm.
 * User: Gassan
 * Date: 9/13/14
 * Time: 10:08 PM
 */

namespace Spliced\Lodestone\Model;


class CharacterEquipment {

    public $primary;

    public $offHand;

    public $head;

    public $body;

    public $hands;

    public $waist;

    public $legs;

    public $feet;

    public $necklace;

    public $earrings;

    public $bracelets;

    public $ring1;

    public $ring2;

    public $soulCrystal;

    /**
     * @param mixed $bracelets
     */
    public function setBracelets($bracelets)
    {
        $this->bracelets = $bracelets;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBracelets()
    {
        return $this->bracelets;
    }

    /**
     * @param mixed $body
     */
    public function setBody(CharacterEquipmentItem $body = null)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $soulCrystal
     */
    public function setSoulCrystal(CharacterEquipmentItem $soulCrystal = null)
    {
        $this->soulCrystal = $soulCrystal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSoulCrystal()
    {
        return $this->soulCrystal;
    }

    /**
     * @param mixed $earrings
     */
    public function setEarrings($earrings)
    {
        $this->earrings = $earrings;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEarrings()
    {
        return $this->earrings;
    }



    /**
     * @param mixed $feet
     */
    public function setFeet(CharacterEquipmentItem $feet = null)
    {
        $this->feet = $feet;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFeet()
    {
        return $this->feet;
    }

    /**
     * @param mixed $hands
     */
    public function setHands(CharacterEquipmentItem $hands = null)
    {
        $this->hands = $hands;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHands()
    {
        return $this->hands;
    }

    /**
     * @param mixed $head
     */
    public function setHead(CharacterEquipmentItem $head = null)
    {
        $this->head = $head;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * @param mixed $legs
     */
    public function setLegs(CharacterEquipmentItem $legs = null)
    {
        $this->legs = $legs;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLegs()
    {
        return $this->legs;
    }

    /**
     * @param mixed $necklace
     */
    public function setNecklace(CharacterEquipmentItem $necklace = null)
    {
        $this->necklace = $necklace;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNecklace()
    {
        return $this->necklace;
    }

    /**
     * @param mixed $offHand
     */
    public function setOffHand(CharacterEquipmentItem $offHand = null)
    {
        $this->offHand = $offHand;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOffHand()
    {
        return $this->offHand;
    }

    /**
     * @param mixed $primary
     */
    public function setPrimary(CharacterEquipmentItem $primary = null)
    {
        $this->primary = $primary;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * @param mixed $ring1
     */
    public function setRing1(CharacterEquipmentItem $ring1 = null)
    {
        $this->ring1 = $ring1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRing1()
    {
        return $this->ring1;
    }

    /**
     * @param mixed $ring2
     */
    public function setRing2(CharacterEquipmentItem $ring2 = null)
    {
        $this->ring2 = $ring2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRing2()
    {
        return $this->ring2;
    }

    /**
     * @param mixed $waist
     */
    public function setWaist(CharacterEquipmentItem $waist = null)
    {
        $this->waist = $waist;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWaist()
    {
        return $this->waist;
    }

    /**
     * @param mixed $braceletss
     */
    public function setWrists(CharacterEquipmentItem $braceletss = null)
    {
        $this->braceletss = $braceletss;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWrists()
    {
        return $this->braceletss;
    }


    public function set($slot, CharacterEquipmentItem $item = null)
    {
        /*if(!isset($this->$slot)){
            throw new \InvalidArgumentException(sprintf('Slot %s is an invalid character equipment slot.', $slot));
        }*/
        $this->$slot = $item;
        return $this;
    }

    public function get($slot)
    {
        /*if(!isset($this->$slot)){
            throw new \InvalidArgumentException(sprintf('Slot %s is an invalid character equipment slot.', $slot));
        }*/
        return $this->$slot;
    }
    
} 