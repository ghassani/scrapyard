<?php

namespace Miva\Migration\Database\Miva;

trait CoreTrait
{


    /**
     * loadStoreKeys
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getStoreKeys()
    {

        $results = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'StoreKeys')
          ->execute()
          ->fetchAll();

        $return = array();
        foreach($results as $row){
            $return[$row['type']] = $row['maxvalue'];
        }

        return $return;
    }


    /**
     * loadStoreKey
     * 
     * @param mixed $type Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getStoreKey($type)
    {
        $query = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'StoreKeys')
          ->where('type = :type')
          ->setParameter('type', $type)
          ->execute();

        $result = $query->fetch();    

        return isset($result['maxvalue']) ? $result['maxvalue'] : 0;
    }

    /**
     * updateStoreKey
     * 
     * @param mixed $type         Description.
     * @param mixed $newIncrement Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateStoreKey($type, $newIncrement)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->update($this->tablePrefix.'StoreKeys', 's')
          ->where('s.type = :type')
          ->set('s.maxvalue', ':maxValue')
          ->setParameter('maxValue', $newIncrement)
          ->setParameter('type', $type)
          ->execute();
    }    


    /**
     * getModules
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getModules()
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from('Modules')
          ->execute()
          ->fetchAll();
    }

    /**
     * getModuleByCode
     * 
     * @param mixed $code Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getModuleByCode($code)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from('Modules')
          ->where('code = :code')
          ->setParameter('code', $code)
          ->execute()
          ->fetch();     
    }

    /**
     * getModuleById
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getModuleById($id)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from('Modules')
          ->where('id = :id')
          ->setParameter('id', $id)
          ->execute()
          ->fetch();   
    }

    /**
     * getModulesByFeature
     * 
     * @param mixed $feature Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getModulesByFeature($feature)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('m.*')
          ->from('Modules', 'm')
          ->innerJoin('m', 'ModuleXFeature', 'f', 'm.id = f.module_id')
          ->where('f.feature = :feature')
          ->setParameter('feature', $feature)
          ->execute()
          ->fetchAll();     
    }

    /**
     * getModulesByFeatures
     * 
     * @param array $features Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getModulesByFeatures(array $features)
    {

        $qb = $this->getConnection()
          ->createQueryBuilder();

         $qb->select('m.*')
          ->from('Modules', 'm')
          ->innerJoin('m', 'ModuleXFeature', 'f', 'm.id = f.module_id');

          $qb->expr()->in('f.feature', $features);

          return $qb->execute()
          ->fetchAll();   
    }

    /**
     * getStoreModulesByFeature
     * 
     * @param array $features Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getStoreModulesByFeature($feature)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'StoreModules', 'm')
          ->innerJoin('m', 'Modules', 'm2', 'm.module_id = m2.id')
          ->where('m.feature = :feature')
          ->setParameter('feature', $feature)
          ->execute()
          ->fetchAll();      
    }

    /**
     * getModuleFeaturesById
     * 
     * @param mixed $moduleId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getModuleFeaturesById($moduleId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('m.*')
          ->from('ModulesXFeature', 'm')
          ->where('m.module_id = :module_id')
          ->setParameter('module_id', $moduleId)
          ->execute()
          ->fetchAll();          
    } 

    /**
     * getModuleFeaturesByCode
     * 
     * @param mixed $moduleCode Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getModuleFeaturesByCode($moduleCode)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('m.*')
          ->from('ModuleXFeature', 'm')
          ->leftJoin('m', 'Modules', 'm2', 'm.module_id = m2.id')
          ->where('m2.code = :code')
          ->setParameter('code', $moduleCode)
          ->execute()
          ->fetchAll();        
    } 

}