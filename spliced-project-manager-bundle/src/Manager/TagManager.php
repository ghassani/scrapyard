<?php

namespace Spliced\Bundle\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\TagEvent;
use Spliced\Bundle\ProjectManagerBundle\Model\TagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * TagManager
 */
class TagManager
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
     * @param TagInterface $tag
     */
    public function save(TagInterface $tag)
    {
        $this->getEntityManager()->persist($tag);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::TAG_SAVE,
            new TagEvent($tag)
        );
    }

    /**
     * @param TagInterface $tag
     */
    public function update(TagInterface $tag)
    {
        $this->getEntityManager()->persist($tag);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::TAG_UPDATE,
            new TagEvent($tag)
        );
    }

    /**
     * @param TagInterface $tag
     * @param bool $flush
     */
    public function delete(TagInterface $tag, $flush = true)
    {
        $this->getEntityManager()->remove($tag);

        if (true == $flush) {
            $this->getEntityManager()->flush();
        }

        $this->getEventDispatcher()->dispatch(
            Event::TAG_DELETE,
            new TagEvent($tag)
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