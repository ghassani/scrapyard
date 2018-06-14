<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectInvoiceLineItem
 */
class ProjectInvoiceLineItem
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $description;

    /**
     * @var float
     */
    private $price;

    /**
     * @var float
     */
    private $quantity;

    /**
     * @var \ProjectInvoice
     * })
     */
    private $invoice;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return ProjectInvoiceLineItem
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set sku
     *
     * @param string $sku
     * @return ProjectInvoiceLineItem
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    
        return $this;
    }

    /**
     * Get sku
     *
     * @return string 
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ProjectInvoiceLineItem
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return ProjectInvoiceLineItem
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set quantity
     *
     * @param float $quantity
     * @return ProjectInvoiceLineItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    
        return $this;
    }

    /**
     * Get quantity
     *
     * @return float 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set quote
     *
     * @param \Spliced\Bundle\ProjectManagerBundle\Entity\ProjectInvoice $invoice
     * @return ProjectInvoiceLineItem
     */
    public function setInvoice(ProjectInvoice $invoice = null)
    {
        $this->invoice = $invoice;
    
        return $this;
    }

    /**
     * Get quote
     *
     * @return \Spliced\Bundle\ProjectManagerBundle\Entity\ProjectInvoice 
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
}