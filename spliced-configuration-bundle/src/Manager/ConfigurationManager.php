<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\Manager;

use AppBundle\DependencyInjection\Configuration;
use Spliced\Bundle\ConfigurationBundle\Event\ConfigurationItemEvent;
use Spliced\Bundle\ConfigurationBundle\Event\ConfigurationLoadEvent;
use Spliced\Bundle\ConfigurationBundle\Event\Event;
use Symfony\Component\HttpFoundation\ParameterBag;
use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationManagerInterface;
use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationItemInterface;
use Spliced\Bundle\ConfigurationBundle\Model\TypeInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class ConfigurationManager implements ConfigurationManagerInterface
{

    protected $types = array();

    protected $parameters;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em, EventDispatcherInterface $eventDispatcher)
	{
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->parameters = new ParameterBag();
        $this->types = new ArrayCollection();
	}
	
	public function init()
	{
		$configuration = $this->getEntityManager()
		  ->getRepository('SplicedConfigurationBundle:Configuration')
		  ->createQueryBuilder('c')
		  ->select('c.key, c.value, c.type')
		  ->getQuery()
		  ->getResult(Query::HYDRATE_ARRAY);
        $newConfig = array();
        foreach ($configuration as $item) {
            $typeHandler = $this->getType($item['type']);
            if (!$typeHandler) {
                continue;
                throw new \RuntimeException(sprintf('ConfigurationManager unable to process type %s. No implementation registered. Available: %s',
                    $item['type'],
                    implode(', ', array_map(function($v){
                        return $v->getKey();
                    }, $this->types->toArray()))
                ));
            }
            $newConfig[$item['key']] = $typeHandler->transformValueToParameter($item['value']);
		}
        $configurationLoadEvent = new ConfigurationLoadEvent($item);
        $this->getEventDispatcher()->dispatch(
            Event::CONFIGURATION_LOAD,
            $configurationLoadEvent
        );
        $this->parameters->replace($configurationLoadEvent->getConfiguration());
	}

    /**
     * @param ConfigurationItemInterface $item
     */
    public function save(ConfigurationItemInterface $item)
    {
        if ($item->getId()) {
            return $this->update($item);
        }
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
        $this->getEventDispatcher()->dispatch(
            Event::CONFIGURATION_SAVE,
            new ConfigurationItemEvent($item)
        );
    }

    /**
     * @param ConfigurationItemInterface $item
     */
    public function update(ConfigurationItemInterface $item)
    {
        if (!$item->getId()) {
            return $this->save($item);
        }
        $typeHandler = $this->getType($item->getType());
        $item->setValue($typeHandler->transformValueToDatabase($item->getValue()));
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
        $this->getEventDispatcher()->dispatch(
            Event::CONFIGURATION_UPDATE,
            new ConfigurationItemEvent($item)
        );
    }

    /**
     * @param ConfigurationItemInterface $item
     * @param bool $flush
     */
    public function delete(ConfigurationItemInterface $item, $flush = true)
    {
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
        $this->getEventDispatcher()->dispatch(
            Event::CONFIGURATION_DELETE,
            new ConfigurationItemEvent($item)
        );
    }
		
	/**
	 * getEntityManager
	 *
	 * @access private
	 * @return EntityManager
	 */
	private function getEntityManager()
	{
		return $this->em;
	}

    /**
     * @return EventDispatcherInterface
     */
    private function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @param TypeInterface $type
     */
    public function addType(TypeInterface $type)
    {
        $this->types->set($type->getKey(), $type);
        return $this;
    }

    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getType($key)
    {
        return $this->types->get($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value)
    {
        return $this->parameters->set($key, $value);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function has($key)
    {
        return $this->parameters->has($key);
    }
    
    public function all()
    {
        return $this->parameters->all();
    }
}