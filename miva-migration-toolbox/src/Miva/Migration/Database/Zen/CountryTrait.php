<?php

namespace Miva\Migration\Database\Zen;

trait CountryTrait
{


    public function getCountries()
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from('countries', 'c')
          ->execute()
          ->fetchAll();
    }   


}