<?php

namespace Miva\Migration\Database\Zen;

trait ZoneTrait
{

    /**
     * getZones
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getZones()
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from('zones')
          ->execute()
          ->fetchAll();
    }

    /**
     * getZoneById
     * 
     * @param mixed $zoneId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getZoneById($zoneId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from('zones')
          ->where('zone_id = :zoneId')
          ->setParameter('zoneId', $zoneId)
          ->execute()
          ->fetch();
    }

    /**
     * getZoneByCode
     * 
     * @param mixed $zoneCode Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getZoneByCode($zoneCode)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from('zones')
          ->where('zone_code = :zoneCode')
          ->setParameter('zoneCode', $zoneCode)
          ->execute()
          ->fetch();
    }

    /**
     * getZoneByNameOrCode
     * 
     * @param mixed $zoneNameOrCode Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getZoneByNameOrCode($zoneNameOrCode)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from('zones')
          ->where('zone_code = :zoneNameOrCode OR zone_name = :zoneNameOrCode')
          ->setParameter('zoneNameOrCode', $zoneNameOrCode)
          ->execute()
          ->fetch();
    }

    
}