<?php
/**
 * Created by PhpStorm.
 * User: Gassan
 * Date: 9/8/14
 * Time: 9:37 PM
 */

namespace Spliced\Lodestone\Model;


class ClassJob {
    const GLADIATOR = 1;
    const PUGILIST = 2;
    const MARAUDER  = 3;
    const LANCER = 4;
    const ARCHER = 5;
    const CONJURER = 6;
    const THAUMATURGE = 7;
    const CARPENTER = 8;
    const BLACKSMITH = 9;
    const ARMORER = 10;
    const GOLDSMITH = 11;
    const LEATHERWORKER = 12;
    const WEAVER = 13;
    const ALCHEMIST = 14;
    const CULINARIAN = 15;
    const MINER = 16;
    const BOTANIST = 17;
    const FISHER = 18;
    const PALADIN = 19;
    const MONK = 20;
    const WARRIOR = 21;
    const DRAGOON = 22;
    const BARD = 23;
    const WHITE_MAGE = 24;
    const BLACK_MAGE = 25;
    const ARCHANIST = 26;
    const SUMMONER = 27;
    const SCHOLAR = 28;
    const ROUGE = null;
    const NINJA = null;

    static $thumbnailMap = array(
        self::GLADIATOR => 'ec5d264e53ea7749d916d7d8bc235ec9c8bb7b51',
        self::PUGILIST => '9fe08b7e2827a51fc216e6407646ffba716a44b8',
        self::MARAUDER  => '5ca476c2166b399e3ec92e8008544fdbea75b6a2',
        self::LANCER => '924ded09293b2a04c4cd662afbf7cda7b0576888',
        self::ARCHER => 'd39804e8810aa3d8e467b7a476d01965510c5d18',
        self::CONJURER => '6157497a98f55a73af4c277f383d0a23551e9e98',
        self::THAUMATURGE => 'e2a98c81ca279607fc1706e5e1b11bc08cac2578',
        self::CARPENTER => 'd41cb306af74bb5407bc74fa865e9207a5ce4899',
        self::BLACKSMITH => '6e0223f41a926eab7e6bc42af7dd29b915999db1',
        self::ARMORER => 'aab4391a4a5633684e1b93174713c1c52f791930',
        self::GOLDSMITH => '605aa74019178eef7d8ba790b3db10ac8e9cd4ca',
        self::LEATHERWORKER => 'f358b50ff0a1b1dcb67490ba8f4c480e01e4edd7',
        self::WEAVER => '131b914b2be4563ec76b870d1fa44aa8da0f1ee6',
        self::ALCHEMIST => '343bce834add76f5d714f33154d0c70e99d495a3',
        self::CULINARIAN => '86f1875ebc31f88eb917283665be128689a9669b',
        self::MINER => '8e82259fcd979378632cde0c9767c15dba3790af',
        self::BOTANIST => '937d3313d9d7ef491319c38a4d4cde4035eb1ab3',
        self::FISHER => '289dbc0b50956ce10a2195a75a22b500a648284e',
        self::PALADIN => '626a1a0927f7d2510a92558e8032831264110f26',
        self::MONK => '8873ffdf5f7c80770bc40f5b82ae1be6fa1f8305',
        self::WARRIOR => '2de279517a8de132f2faad4986a507ed728a067f',
        self::DRAGOON => '36ce9c4cc01581d4f900102cd51e09c60c3876a6',
        self::BARD => '7a72ef2dc1918f56e573dd28cffcec7e33a595df',
        self::WHITE_MAGE => 'c460e288d5db83ebc90d0654bee6d0d0a0a9582d',
        self::BLACK_MAGE => '98d95dec1f321f111439032b64bc42b98c063f1b',
        self::ARCHANIST => '59fde9fca303490477962039f6cd0d0101caeabe',
        self::SUMMONER => '2c38a1b928c88fd20bcc74fe0b4d9ba0a8f56f67',
        self::SCHOLAR => 'ee5788ae748ff28a503fecbec2a523dbc6875298',
        self::ROUGE => '',
        self::NINJA => '',
    );
    
    static $classJobs;

    static function getClassJobs()
    {
        if (isset(static::$classJobs) && static::$classJobs) {
            return static::$classJobs;
        }

        $reflector = new \ReflectionClass('Spliced\\Lodestone\Model\ClassJob');

        $return = array();

        foreach($reflector->getConstants() as $constantKey => $constantValue) {
            $return[$constantValue] = $constantKey;
        }

        static::$classJobs = $return;

        return $return;
    }
} 