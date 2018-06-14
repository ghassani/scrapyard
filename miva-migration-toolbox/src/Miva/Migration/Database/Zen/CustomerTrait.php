<?php

namespace Miva\Migration\Database\Zen;

trait CustomerTrait
{

    /**
     * loadCustomers
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getCustomers()
    {


        $results = $this->getConnection()
          ->createQueryBuilder()
          ->select('
               c.customers_id AS id,
               c.customers_firstname AS fname,
               c.customers_lastname AS lname,
               c.customers_email_address AS email,
               c.customers_telephone AS phone,
               c.customers_fax AS fax,
               c.customers_password AS password,
               c.customers_newsletter AS newsletter,
               c.customers_id AS id,
               c.customers_default_address_id,

               a.address_book_id,
               a.entry_company AS company,
               a.entry_firstname AS address_fname,
               a.entry_lastname AS address_lname,
               a.entry_street_address AS address,
               a.entry_suburb AS address2,
               a.entry_postcode AS zipcode,
               a.entry_city AS city,
               a.entry_state AS state_alt,
               c2.countries_iso_code_2 AS country,
               z.zone_code AS state,

               a2.address_book_id AS default_address_book_id,
               a2.entry_company AS default_company,
               a2.entry_firstname AS default_address_fname,
               a2.entry_lastname AS default_address_lname,
               a2.entry_street_address AS default_address,
               a2.entry_suburb AS default_address2,
               a2.entry_postcode AS default_zipcode,
               a2.entry_city AS default_city,
               a2.entry_state AS default_state_alt,
               c3.countries_iso_code_2 AS default_country,
               z2.zone_code AS default_state
            ')
          ->from('customers', 'c')

          ->leftJoin('c',  'address_book', 'a',  'a.customers_id = c.customers_id')
          ->leftJoin('c',  'address_book', 'a2', 'a2.address_book_id = c.customers_default_address_id')
          ->leftJoin('a',  'countries',    'c2', 'a.entry_country_id = c2.countries_id')
          ->leftJoin('a2', 'countries', 'c3', 'a2.entry_country_id = c3.countries_id')
          ->leftJoin('a',  'zones',        'z',  'a.entry_zone_id = z.zone_id')
          ->leftJoin('a2', 'zones',        'z2', 'a2.entry_zone_id = z2.zone_id')
          ->execute()
          ->fetchAll();

        $return = array();
        
        foreach($results as $r){
            $return[$r['id']] = $r;
        }
        
        return $return;
    }

}