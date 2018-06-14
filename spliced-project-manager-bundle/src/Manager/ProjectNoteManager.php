<?php

namespace Spliced\Bundle\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectNoteEvent;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectNoteInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ProjectNoteManager
 */
class ProjectNoteManager
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
     * @param ProjectNoteInterface $projectNote
     */
    public function save(ProjectNoteInterface $projectNote)
    {
        $this->getEntityManager()->persist($projectNote);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_NOTE_SAVE,
            new ProjectNoteEvent($projectNote->getProject(), $projectNote)
        );
    }

    /**
     * @param ProjectNoteInterface $projectNote
     */
    public function update(ProjectNoteInterface $projectNote)
    {
        $this->getEntityManager()->persist($projectNote);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_NOTE_UPDATE,
            new ProjectNoteEvent($projectNote->getProject(), $projectNote)
        );
    }

    /**
     * @param ProjectNoteInterface $projectNote
     * @param bool $flush
     */
    public function delete(ProjectNoteInterface $projectNote, $flush = true)
    {
        $this->getEntityManager()->remove($projectNote);

        if (true == $flush) {
            $this->getEntityManager()->flush();
        }

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_NOTE_DELETE,
            new ProjectNoteEvent($projectNote->getProject(), $projectNote)
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