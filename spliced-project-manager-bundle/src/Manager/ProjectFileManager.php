<?php

namespace Spliced\Bundle\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectFileInterface;
use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectFileEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ProjectFileManager
 */
class ProjectFileManager
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
     * @param ProjectFileInterface $projectFile
     */
    public function save(ProjectFileInterface $projectFile)
    {
        $this->getEntityManager()->persist($projectFile);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_FILE_SAVE,
            new ProjectFileEvent($projectFile->getProject(), $projectFile)
        );
    }

    /**
     * @param ProjectFileInterface $projectFile
     */
    public function update(ProjectFileInterface $projectFile)
    {
        $this->getEntityManager()->persist($projectFile);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_FILE_UPDATE,
            new ProjectFileEvent($projectFile->getProject(), $projectFile)
        );
    }

    /**
     * @param ProjectFileInterface $projectFile
     * @param bool $flush
     */
    public function delete(ProjectFileInterface $projectFile, $flush = true)
    {
        $this->getEntityManager()->remove($projectFile);

        if (true == $flush) {
            $this->getEntityManager()->flush();
        }

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_FILE_DELETE,
            new ProjectFileEvent($projectFile->getProject(), $projectFile)
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