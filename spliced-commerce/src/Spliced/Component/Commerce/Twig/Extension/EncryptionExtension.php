<?php

namespace Spliced\Component\Commerce\Twig\Extension;

use Spliced\Component\Commerce\Security\Encryption\EncryptionManager;

class EncryptionExtension extends \Twig_Extension
{
    /** @var EncryptionManager */
    protected $encryptionManager;

    /**
     * Constructor 
     * 
     * @param EncryptionManager $encryptionManager
     */
    public function __construct(EncryptionManager $encryptionManager)
    {
       $this->encryptionManager = $encryptionManager;
    }

    /**
     * getEncryptionManager
     * 
     * @return EncryptionManager
     */
    protected function getEncryptionManager()
    {
        return $this->encryptionManager;    
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'commerce_encrypt' => new \Twig_Function_Method($this, 'encrypt'),
            'commerce_decrypt' => new \Twig_Function_Method($this, 'decrypt'),
        );
    }

    /**
     * encrypt
     *
     * @param string $protectCode
     * @param string $value
     */
    public function encrypt($protectCode, $value){
        return $this->getEncryptionManager()->encrypt($protectCode, $value);
    }

    /**
     * decrypt
     * 
     * @param string $protectCode
     * @param string $encryptedValue
     */
    public function decrypt($protectCode, $encryptedValue)
    {
        return $this->getEncryptionManager()->decrypt($protectCode, $encryptedValue);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_encryption';
    }

}
