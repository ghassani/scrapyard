<?php

namespace Spliced\Bundle\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectMediaEvent;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectMediaInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ProjectMediaManager
 */
class ProjectMediaManager
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
     * @param ProjectMediaInterface $projectMedia
     */
    public function save(ProjectMediaInterface $projectMedia)
    {
        $this->getEntityManager()->persist($projectMedia);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_MEDIA_SAVE,
            new ProjectMediaEvent($projectMedia->getProject(), $projectMedia)
        );
    }

    /**
     * @param ProjectMediaInterface $projectMedia
     */
    public function update(ProjectMediaInterface $projectMedia)
    {
        $this->getEntityManager()->persist($projectMedia);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_MEDIA_UPDATE,
            new ProjectMediaEvent($projectMedia->getProject(), $projectMedia)
        );
    }

    /**
     * @param ProjectMediaInterface $projectMedia
     * @param bool $flush
     */
    public function delete(ProjectMediaInterface $projectMedia, $flush = true)
    {
        $this->getEntityManager()->remove($projectMedia);

        if (true == $flush){
            $this->getEntityManager()->flush();
        }

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_MEDIA_DELETE,
            new ProjectMediaEvent($projectMedia->getProject(), $projectMedia)
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