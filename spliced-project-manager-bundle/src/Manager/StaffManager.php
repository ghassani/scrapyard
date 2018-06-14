<?php

namespace Spliced\Bundle\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\StaffEvent;
use Spliced\Bundle\ProjectManagerBundle\Model\StaffInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * StaffManager
 */
class StaffManager
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
     * @param StaffInterface $staff
     */
    public function save(StaffInterface $staff)
    {
        $this->getEntityManager()->persist($staff);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::STAFF_SAVE,
            new StaffEvent($staff)
        );
    }

    /**
     * @param StaffInterface $staff
     */
    public function update(StaffInterface $staff)
    {
        $this->getEntityManager()->persist($staff);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::STAFF_UPDATE,
            new StaffEvent($staff)
        );
    }

    /**
     * @param StaffInterface $staff
     * @param bool $flush
     */
    public function delete(StaffInterface $staff, $flush = true)
    {
        $this->getEntityManager()->persist($staff);

        if (true == $flush) {
            $this->getEntityManager()->flush();
        }

        $this->getEventDispatcher()->dispatch(
            Event::STAFF_DELETE,
            new StaffEvent($staff)
        );
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