<?php

namespace Spliced\Bundle\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectStaffEvent;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectStaffInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ProjectStaffManager
 */
class ProjectStaffManager
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
     * @param ProjectStaffInterface $projectStaff
     */
    public function save(ProjectStaffInterface $projectStaff)
    {
        $this->getEntityManager()->persist($projectStaff);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_STAFF_SAVE,
            new ProjectStaffEvent($projectStaff->getProject(), $projectStaff)
        );
    }

    /**
     * @param ProjectStaffInterface $projectStaff
     */
    public function update(ProjectStaffInterface $projectStaff)
    {
        $this->getEntityManager()->persist($projectStaff);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_STAFF_UPDATE,
            new ProjectStaffEvent($projectStaff->getProject(), $projectStaff)
        );
    }

    /**
     * @param ProjectStaffInterface $projectStaff
     * @param bool $flush
     */
    public function delete(ProjectStaffInterface $projectStaff, $flush = true)
    {
        $this->getEntityManager()->remove($projectStaff);

        if (true == $flush) {
            $this->getEntityManager()->flush();
        }


        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_STAFF_DELETE,
            new ProjectStaffEvent($projectStaff->getProject(), $projectStaff)
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