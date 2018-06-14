<?php

namespace Miva\Migration\Database\Miva;

trait CustomerTrait
{
    /**
     * loadCustomer
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCustomer($id)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Customers')
          ->where('id = :id')
          ->setParameter('id', $id)
          ->execute()
          ->fetch();
    }

    /**
     * getCustomerByLogin
     * 
     * @param mixed $login Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCustomerByLogin($login)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Customers')
          ->where('login = :login')
          ->setParameter('login', $login)
          ->execute()
          ->fetch();
    }

    /**
     * getCustomerByLogin
     * 
     * @param mixed $login Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCustomerByLostPasswordEmail($email)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Customers')
          ->where('pw_email = :pw_email')
          ->setParameter('pw_email', $email)
          ->execute()
          ->fetch();
    }

    public function getCustomerBy($field, $value)
    {
        $qb = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Customers');

          if (is_array($field)) {
            foreach ($field as $f) {
              $qb->orWhere($f.' = :value');
            }
          } else {
            $qb->where($field.' = :value');
          }

          return $qb->setParameter('value', $value)
          ->execute()
          ->fetch();
    }

    /**
     * updateCustomer
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateCustomer(array $customer)
    {
        if(!isset($customer['id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update($this->tablePrefix.'Customers', $customer, array('id' => $customer['id']), array());
    }


    /**
     * insertCustomer
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertCustomer(array $customer)
    {
        return $this->getConnection()->insert($this->tablePrefix.'Customers', $customer);    
    }
    
    public function deleteCustomer(array $customer)
    {
        if (!$customer['id']) {
            return false;
        }
        
        return $this->getConnection()->delete($this->tablePrefix.'Customers', array('id' => $customer['id']));    
    }

}