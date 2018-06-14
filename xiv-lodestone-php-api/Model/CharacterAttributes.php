<?php
/**
 * Created by PhpStorm.
 * User: Gassan
 * Date: 9/14/14
 * Time: 12:21 PM
 */

namespace Spliced\Lodestone\Model;


class CharacterAttributes {

    protected $attributes = array(
        'hp' => 0,
        'mp' => 0,
        'tp' => 1000,
        'strength' => 0,
        'dexterity' => 0,
        'vitality' => 0,
        'intelligence' => 0,
        'mind' => 0,
        'piety' => 0,
        'perception' => 0,
        'gathering' => 0,
        'control' => 0,
        'craftsmanship' => 0,
        'fire' => 0,
        'ice' => 0,
        'wind' => 0,
        'earth' => 0,
        'lightning' => 0,
        'water' => 0,
        'accuracy' => 0,
        'critical_hit_rate' => 0,
        'determination' => 0,
        'defense' => 0,
        'parry' => 0,
        'magic_defense' => 0,
        'attack_power' => 0,
        'skill_speed' => 0,
        'attack_magic_potency' => 0,
        'healing_magic_potency' => 0,
        'spell_speed' => 0,
        'slow_resistance' => 0,
        'silence_resistance' => 0,
        'blind_resistance' => 0,
        'poison_resistance' => 0,
        'stun_resistance' => 0,
        'sleep_resistance' => 0,
        'bind_resistance' => 0,
        'heavy_resistance' => 0,
        'pvp_properties' => 0,
        'morale' => 0,
        'physical_resistances' => 0,
        'slashing' => 0,
        'piercing' => 0,
        'blunt' => 0,
    );


    public function set($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function get($name, $defaultReturn = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $defaultReturn;
    }

    /**
     * @param mixed $accuracy
     */
    public function setAccuracy($accuracy)
    {
        $this->attributes['accuracy'] = $accuracy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccuracy()
    {
        return $this->attributes['accuracy'];
    }

    /**
     * @param mixed $attack_magic_potency
     */
    public function setAttackMagicPotency($attack_magic_potency)
    {
        $this->attributes['attack_magic_potency'] = $attack_magic_potency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttackMagicPotency()
    {
        return $this->attributes['attack_magic_potency'];
    }

    /**
     * @param mixed $attack_power
     */
    public function setAttackPower($attack_power)
    {
        $this->attributes['attack_power'] = $attack_power;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttackPower()
    {
        return $this->attributes['attack_power'];
    }

    /**
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes['attributes'] = $attributes;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes['attributes'];
    }

    /**
     * @param mixed $bind_resistance
     */
    public function setBindResistance($bind_resistance)
    {
        $this->attributes['bind_resistance'] = $bind_resistance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBindResistance()
    {
        return $this->attributes['bind_resistance'];
    }

    /**
     * @param mixed $blind_resistance
     */
    public function setBlindResistance($blind_resistance)
    {
        $this->attributes['blind_resistance'] = $blind_resistance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlindResistance()
    {
        return $this->attributes['blind_resistance'];
    }

    /**
     * @param mixed $blunt
     */
    public function setBlunt($blunt)
    {
        $this->attributes['blunt'] = $blunt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlunt()
    {
        return $this->attributes['blunt'];
    }

    /**
     * @param mixed $control
     */
    public function setControl($control)
    {
        $this->attributes['control'] = $control;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getControl()
    {
        return $this->attributes['control'];
    }

    /**
     * @param mixed $craftsmanship
     */
    public function setCraftsmanship($craftsmanship)
    {
        $this->attributes['craftsmanship'] = $craftsmanship;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCraftsmanship()
    {
        return $this->attributes['craftsmanship'];
    }

    /**
     * @param mixed $critical_hit_rate
     */
    public function setCriticalHitRate($critical_hit_rate)
    {
        $this->attributes['critical_hit_rate'] = $critical_hit_rate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriticalHitRate()
    {
        return $this->attributes['critical_hit_rate'];
    }

    /**
     * @param mixed $defense
     */
    public function setDefense($defense)
    {
        $this->attributes['defense'] = $defense;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefense()
    {
        return $this->attributes['defense'];
    }

    /**
     * @param mixed $determination
     */
    public function setDetermination($determination)
    {
        $this->attributes['determination'] = $determination;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetermination()
    {
        return $this->attributes['determination'];
    }

    /**
     * @param mixed $dexterity
     */
    public function setDexterity($dexterity)
    {
        $this->attributes['dexterity'] = $dexterity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDexterity()
    {
        return $this->attributes['dexterity'];
    }

    /**
     * @param mixed $earth
     */
    public function setEarth($earth)
    {
        $this->attributes['earth'] = $earth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEarth()
    {
        return $this->attributes['earth'];
    }

    /**
     * @param mixed $fire
     */
    public function setFire($fire)
    {
        $this->attributes['fire'] = $fire;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFire()
    {
        return $this->attributes['fire'];
    }

    /**
     * @param mixed $gathering
     */
    public function setGathering($gathering)
    {
        $this->attributes['gathering'] = $gathering;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGathering()
    {
        return $this->attributes['gathering'];
    }

    /**
     * @param mixed $healing_magic_potency
     */
    public function setHealingMagicPotency($healing_magic_potency)
    {
        $this->attributes['healing_magic_potency'] = $healing_magic_potency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHealingMagicPotency()
    {
        return $this->attributes['healing_magic_potency'];
    }

    /**
     * @param mixed $heavy_resistance
     */
    public function setHeavyResistance($heavy_resistance)
    {
        $this->attributes['heavy_resistance'] = $heavy_resistance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeavyResistance()
    {
        return $this->attributes['heavy_resistance'];
    }

    /**
     * @param mixed $ice
     */
    public function setIce($ice)
    {
        $this->attributes['ice'] = $ice;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIce()
    {
        return $this->attributes['ice'];
    }

    /**
     * @param mixed $intelligence
     */
    public function setIntelligence($intelligence)
    {
        $this->attributes['intelligence'] = $intelligence;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIntelligence()
    {
        return $this->attributes['intelligence'];
    }

    /**
     * @param mixed $lightning
     */
    public function setLightning($lightning)
    {
        $this->attributes['lightning'] = $lightning;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLightning()
    {
        return $this->attributes['lightning'];
    }

    /**
     * @param mixed $magic_defense
     */
    public function setMagicDefense($magic_defense)
    {
        $this->attributes['magic_defense'] = $magic_defense;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMagicDefense()
    {
        return $this->attributes['magic_defense'];
    }

    /**
     * @param mixed $mind
     */
    public function setMind($mind)
    {
        $this->attributes['mind'] = $mind;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMind()
    {
        return $this->attributes['mind'];
    }

    /**
     * @param mixed $morale
     */
    public function setMorale($morale)
    {
        $this->attributes['morale'] = $morale;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMorale()
    {
        return $this->attributes['morale'];
    }

    /**
     * @param mixed $parry
     */
    public function setParry($parry)
    {
        $this->attributes['parry'] = $parry;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParry()
    {
        return $this->attributes['parry'];
    }

    /**
     * @param mixed $perception
     */
    public function setPerception($perception)
    {
        $this->attributes['perception'] = $perception;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPerception()
    {
        return $this->attributes['perception'];
    }

    /**
     * @param mixed $physical_resistances
     */
    public function setPhysicalResistances($physical_resistances)
    {
        $this->attributes['physical_resistances'] = $physical_resistances;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhysicalResistances()
    {
        return $this->attributes['physical_resistances'];
    }

    /**
     * @param mixed $piercing
     */
    public function setPiercing($piercing)
    {
        $this->attributes['piercing'] = $piercing;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPiercing()
    {
        return $this->attributes['piercing'];
    }

    /**
     * @param mixed $piety
     */
    public function setPiety($piety)
    {
        $this->attributes['piety'] = $piety;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPiety()
    {
        return $this->attributes['piety'];
    }

    /**
     * @param mixed $poison_resistance
     */
    public function setPoisonResistance($poison_resistance)
    {
        $this->attributes['poison_resistance'] = $poison_resistance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoisonResistance()
    {
        return $this->attributes['poison_resistance'];
    }

    /**
     * @param mixed $pvp_properties
     */
    public function setPvpProperties($pvp_properties)
    {
        $this->attributes['pvp_properties'] = $pvp_properties;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPvpProperties()
    {
        return $this->attributes['pvp_properties'];
    }

    /**
     * @param mixed $silence_resistance
     */
    public function setSilenceResistance($silence_resistance)
    {
        $this->attributes['silence_resistance'] = $silence_resistance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSilenceResistance()
    {
        return $this->attributes['silence_resistance'];
    }

    /**
     * @param mixed $skill_speed
     */
    public function setSkillSpeed($skill_speed)
    {
        $this->attributes['skill_speed'] = $skill_speed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSkillSpeed()
    {
        return $this->attributes['skill_speed'];
    }

    /**
     * @param mixed $slashing
     */
    public function setSlashing($slashing)
    {
        $this->attributes['slashing'] = $slashing;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlashing()
    {
        return $this->attributes['slashing'];
    }

    /**
     * @param mixed $sleep_resistance
     */
    public function setSleepResistance($sleep_resistance)
    {
        $this->attributes['sleep_resistance'] = $sleep_resistance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSleepResistance()
    {
        return $this->attributes['sleep_resistance'];
    }

    /**
     * @param mixed $slow_resistance
     */
    public function setSlowResistance($slow_resistance)
    {
        $this->attributes['slow_resistance'] = $slow_resistance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlowResistance()
    {
        return $this->attributes['slow_resistance'];
    }

    /**
     * @param mixed $spell_speed
     */
    public function setSpellSpeed($spell_speed)
    {
        $this->attributes['spell_speed'] = $spell_speed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpellSpeed()
    {
        return $this->attributes['spell_speed'];
    }

    /**
     * @param mixed $strength
     */
    public function setStrength($strength)
    {
        $this->attributes['strength'] = $strength;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStrength()
    {
        return $this->attributes['strength'];
    }

    /**
     * @param mixed $stun_resistance
     */
    public function setStunResistance($stun_resistance)
    {
        $this->attributes['stun_resistance'] = $stun_resistance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStunResistance()
    {
        return $this->attributes['stun_resistance'];
    }

    /**
     * @param mixed $vitality
     */
    public function setVitality($vitality)
    {
        $this->attributes['vitality'] = $vitality;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVitality()
    {
        return $this->attributes['vitality'];
    }

    /**
     * @param mixed $water
     */
    public function setWater($water)
    {
        $this->attributes['water'] = $water;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWater()
    {
        return $this->attributes['water'];
    }

    /**
     * @param mixed $wind
     */
    public function setWind($wind)
    {
        $this->attributes['wind'] = $wind;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWind()
    {
        return $this->attributes['wind'];
    }

} 