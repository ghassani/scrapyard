<?php

namespace Spliced\Bundle\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectTagEvent;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectTagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ProjectTagManager
 */
class ProjectTagManager
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
     * @param ProjectTagInterface $projectTag
     */
    public function save(ProjectTagInterface $projectTag)
    {
        $this->getEntityManager()->persist($projectTag);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_TAG_SAVE,
            new ProjectTagEvent($projectTag->getProject(), $projectTag)
        );
    }

    /**
     * @param ProjectTagInterface $projectTag
     */
    public function update(ProjectTagInterface $projectTag)
    {
        $this->getEntityManager()->persist($projectTag);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_TAG_UPDATE,
            new ProjectTagEvent($projectTag->getProject(), $projectTag)
        );
    }

    /**
     * @param ProjectTagInterface $projectTag
     * @param bool $flush
     */
    public function delete(ProjectTagInterface $projectTag, $flush = true)
    {
        $this->getEntityManager()->remove($projectTag);

        if (true == $flush) {
            $this->getEntityManager()->flush();
        }


        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_TAG_DELETE,
            new ProjectTagEvent($projectTag->getProject(), $projectTag)
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