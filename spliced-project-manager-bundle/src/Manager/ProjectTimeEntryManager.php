<?php

namespace Spliced\Bundle\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectTimeEntryEvent;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectTimeEntryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ProjectTimeEntryManager
 */
class ProjectTimeEntryManager
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
     * @param ProjectTimeEntryInterface $projectTimeEntry
     */
    public function save(ProjectTimeEntryInterface $projectTimeEntry)
    {

        if ($projectTimeEntry->getEntryTime() instanceof \DateTime) {
            $projectTimeEntry->setEntryTime($projectTimeEntry->getEntryTime()->format('h:m'));
        }

        $this->getEntityManager()->persist($projectTimeEntry);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_TIME_ENTRY_SAVE,
            new ProjectTimeEntryEvent($projectTimeEntry->getProject(), $projectTimeEntry)
        );
    }

    /**
     * @param ProjectTimeEntryInterface $projectTimeEntry
     */
    public function update(ProjectTimeEntryInterface $projectTimeEntry)
    {
        if ($projectTimeEntry->getEntryTime() instanceof \DateTime) {
            $projectTimeEntry->setEntryTime($projectTimeEntry->getEntryTime()->format('h:m'));
        }

        $this->getEntityManager()->persist($projectTimeEntry);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_TIME_ENTRY_UPDATE,
            new ProjectTimeEntryEvent($projectTimeEntry->getProject(), $projectTimeEntry)
        );
    }

    /**
     * @param ProjectTimeEntryInterface $projectTimeEntry
     * @param bool $flush
     */
    public function delete(ProjectTimeEntryInterface $projectTimeEntry, $flush = true)
    {
        $this->getEntityManager()->remove($projectTimeEntry);

        if (true == $flush){
            $this->getEntityManager()->flush();
        }

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_TIME_ENTRY_DELETE,
            new ProjectTimeEntryEvent($projectTimeEntry->getProject(), $projectTimeEntry)
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