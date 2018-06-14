<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr;

/**
 * ConfigurationDocumentRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class ConfigurationRepository extends EntityRepository implements ConfigurationRepositoryInterface
{
    /**
    * {@inheritDoc}
    */
    public function getConfiguration($cache = true, $hydrate = true)
    {
        return $this->createQueryBuilder('configuration')
          ->select('configuration')
          ->orderBy('configuration.key', 'ASC')
          ->getQuery()
          ->getResult(Query::HYDRATE_ARRAY);
    }
    
    
    /**
     * getConfigurationForGroup
     * 
     * Get All Configuration Entries for a Given Group and optionally Child Group
     * 
     * @param string $groupName - The name of the group
     * @param string $childGroup - Optional name of child group to match
     * @param string $orderBy - Field to order the results
     * @param string $sort - Sort ASC or DESC
     * 
     * @return Collection
     */
    public function getConfigurationForGroup($groupName, $childGroup = null, $orderBy = 'position', $sort = 'ASC')
    {
    	$query = $this->createQueryBuilder('configuration')
    	->select('configuration')
    	->where('configuration.group = :groupName')
    	->setParameter('groupName', $groupName)
    	->orderBy('configuration.'.$orderBy, $sort);
    	
    	if (!is_null($childGroup)) {
    		$query->andWhere('configuration.childGroup = :childGroup')
    		  ->setParameter('childGroup', $childGroup);
    	}
    	return $query->getQuery()
    	->getResult();
    }
    
     
    /**
     * getConfigurationGroups
     * 
     * Retreive all configuration group names
     * 
     * @return array
     */
    public function getConfigurationGroups($orderBy = 'position', $sort = 'ASC')
    {
    	$groups = $this->createQueryBuilder('configuration')
    	->select('DISTINCT(configuration.group)')
    	->orderBy('configuration.'.$orderBy, $sort)
    	->getQuery()
    	->getResult(Query::HYDRATE_ARRAY);

    	return array_map(function($group){
    		return $group['group'];
    	},$groups); 
    }
}
