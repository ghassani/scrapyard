<?php

namespace Spliced\Bundle\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectEvent;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ProjectManager
 */
class ProjectManager
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
     * @param ProjectInterface $project
     */
    public function save(ProjectInterface $project)
    {
        $this->getEntityManager()->persist($project);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_SAVE,
            new ProjectEvent($project)
        );
    }

    /**
     * @param ProjectInterface $project
     */
    public function update(ProjectInterface $project)
    {
        $this->getEntityManager()->persist($project);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_UPDATE,
            new ProjectEvent($project)
        );
    }

    /**
     * @param ProjectInterface $project
     * @param bool $flush
     */
    public function delete(ProjectInterface $project, $flush = true)
    {
        $this->getEntityManager()->remove($project);

        if (true == $flush){
            $this->getEntityManager()->flush();
        }

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_DELETE,
            new ProjectEvent($project)
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