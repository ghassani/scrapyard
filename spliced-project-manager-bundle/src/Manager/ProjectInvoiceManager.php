<?php

namespace Spliced\Bundle\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInvoiceInterface;
use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectInvoiceEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ProjectInvoiceManager
 */
class ProjectInvoiceManager
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
     * @param ProjectInvoiceInterface $projectInvoice
     */
    public function save(ProjectInvoiceInterface $projectInvoice)
    {
        $this->getEntityManager()->persist($projectInvoice);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_INVOICE_SAVE,
            new ProjectInvoiceEvent($projectInvoice->getProject(), $projectInvoice)
        );
    }

    /**
     * @param ProjectInvoiceInterface $projectInvoice
     */
    public function update(ProjectInvoiceInterface $projectInvoice)
    {
        $this->getEntityManager()->persist($projectInvoice);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_INVOICE_UPDATE,
            new ProjectInvoiceEvent($projectInvoice->getProject(), $projectInvoice)
        );
    }

    /**
     * @param ProjectInvoiceInterface $projectInvoice
     * @param bool $flush
     */
    public function delete(ProjectInvoiceInterface $projectInvoice, $flush = true)
    {
        $this->getEntityManager()->remove($projectInvoice);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }

        $this->getEventDispatcher()->dispatch(
            Event::PROJECT_INVOICE_DELETE,
            new ProjectInvoiceEvent($projectInvoice->getProject(), $projectInvoice)
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