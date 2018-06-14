<?php

namespace Miva\Migration\Database\Miva;

trait TemplateTrait
{


    /**
     * getManagedTemplates
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getManagedTemplates()
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'ManagedTemplates')
          ->execute()
          ->fetchAll();      
    } 

    /**
     * getManagedTemplate
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getManagedTemplate($id)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'ManagedTemplates', 'm')
          ->leftJoin('m', 'ManagedTemplateVersions', 'v', 'ON v.id = m.current_id')
          ->where('m.id = :id')
          ->setParameter('id', $id)
          ->execute()
          ->fetchAll();          
    } 


    /**
     * insertManagedTemplate
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertManagedTemplate(array $managedTemplate)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'ManagedTemplates', 
            $managedTemplate
        );     
    }


    /**
     * insertManagedTemplateVersion
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertManagedTemplateVersion(array $managedTemplateVersion)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'ManagedTemplateVersions', 
            $managedTemplateVersion
        );         
    }

    /**
     * getManagedTemplateVersion
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getManagedTemplateVersion($id)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'ManagedTemplateVersions')
          ->where('id = :id')
          ->setParameter('id', $id)
          ->execute()
          ->fetch();      
    } 


    /**
     * getItems
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getItems()
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Items')
          ->execute()
          ->fetchAll();    
    }

    /**
     * getItemById
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getItemById($id)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Items')
          ->where('id = :id')
          ->setParameter('id', $id)
          ->execute()
          ->fetch();    
    }

    /**
     * getItemByCode
     * 
     * @param mixed $code Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getItemByCode($code)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Items')
          ->where('code = :code')
          ->setParameter('code', $code)
          ->execute()
          ->fetch(); 
    }
}