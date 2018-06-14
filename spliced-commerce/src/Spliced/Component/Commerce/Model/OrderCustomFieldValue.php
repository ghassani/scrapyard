<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderCustomFieldValue
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="customer_order_custom_field_value")
 * @ORM\Entity()
 */
abstract class OrderCustomFieldValue implements OrderCustomFieldValueInterface
{

    /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @var string $fieldValue
     *
     * @ORM\Column(name="field_value", type="string", nullable=false)
     */
    protected $fieldValue;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="customFields")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $order;
    

    /**
     * getId
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
        return $this;
    }
    
    /**
     * {@inheritDoc}
    */
    public function getOrder()
    {
        return $this->order;
    }
    
    /**
     * {@inheritDoc}
    */
    public function setField(CheckoutCustomFieldInterface $field)
    {
        $this->field = $field;
        return $this;
    }
    
    /**
     * {@inheritDoc}
    */
    public function getField()
    {
        return $this->field;
    }
    
    /**
     * {@inheritDoc}
    */
    public function setFieldValue($fieldValue)
    {
        $this->fieldValue = $fieldValue;
        return $this;
    }
    
    /**
     * {@inheritDoc}
    */
    public function getFieldValue()
    {
        return $this->fieldValue;
    }
    
}