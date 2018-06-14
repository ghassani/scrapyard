<?php

namespace Spliced\Bundle\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectAttributeInterface;
use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectAttributeEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ProjectAttributeManager
 */
class ProjectAttributeManager
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
     * @param ProjectAttributeInterface $projectAttribute
     */
    public function save(ProjectAttributeInterface $projectAttribute)
    {

        $this->getEntityManager()->persist($projectAttribute);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_ATTRIBUTE_SAVE,
            new ProjectAttributeEvent($projectAttribute->getProject(), $projectAttribute)
        );
    }

    /**
     * @param ProjectAttributeInterface $projectAttribute
     */
    public function update(ProjectAttributeInterface $projectAttribute)
    {
        $this->getEntityManager()->persist($projectAttribute);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_ATTRIBUTE_UPDATE,
            new ProjectAttributeEvent($projectAttribute->getProject(), $projectAttribute)
        );
    }

    /**
     * @param ProjectAttributeInterface $projectAttribute
     * @param bool $flush
     */
    public function delete(ProjectAttributeInterface $projectAttribute, $flush = true)
    {
        $this->getEntityManager()->remove($projectAttribute);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_ATTRIBUTE_DELETE,
            new ProjectAttributeEvent($projectAttribute->getProject(), $projectAttribute)
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