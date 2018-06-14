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

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContactMessage
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="contact_message")
 * @ORM\Entity()
 */
abstract class ContactMessage implements ContactMessageInterface
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
     * @ORM\OneToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;
    
    /**
     * @var string $name
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=150, nullable=false)
     */
    protected $name;
    
    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=false)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = false
     * )
     * @Assert\NotBlank()
     */
    protected $email;
    
    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=150, nullable=false)
     */
    protected $phone;
    
    /**
     * @var string $subject
     *
     * @ORM\Column(name="subject", type="string", length=150, nullable=false)
     * @Assert\NotBlank()
     */
    protected $subject;
    

    /**
     * @var string $comment
     *
     * @ORM\Column(name="comment", type="text", length=150, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=50,
     *     minMessage="Your comment must have at least {{ limit }} characters."
     * )
     */
    protected $comment;
    
    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    protected $updatedAt;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * getCustomer
     *
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customer;
    }
    
    /**
     * setCustomer
     *
     * @param CustomerInterface
     */
    public function setCustomer(CustomerInterface $customer = null)
    {
        $this->customer = $customer;
        return $this;
    }
    
    /**
     * getName
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * setName
     *
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * getEmail
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * setEmail
     *
     * @param string
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    
    /**
     * getPhone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
    
    /**
     * setPhone
     *
     * @param string
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }
    
    /**
     * getSubject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }
    
    /**
     * setSubject
     *
     * @param string
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * getComment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
    
    /**
     * setComment
     *
     * @param string
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }
    
    /**
     * getCreatedAt
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * setCreatedAt
     *
     * @param string
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    /**
     * getUpdatedAt
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    /**
     * setUpdatedAt
     *
     * @param string
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}