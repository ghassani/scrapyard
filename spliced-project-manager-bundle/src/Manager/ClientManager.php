<?php

namespace Spliced\Bundle\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ProjectManagerBundle\Model\ClientInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ClientManager
 */
class ClientManager
{
    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param ClientInterface $client
     */
    public function save(ClientInterface $client)
    {

    }

    /**
     * @param ClientInterface $client
     */
    public function update(ClientInterface $client)
    {

    }

    /**
     * @param ClientInterface $client
     * @param bool $flush
     */
    public function delete(ClientInterface $client, $flush = true)
    {

    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

}