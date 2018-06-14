<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Security\Encryption;

/**
 * EncryptorInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface EncryptorInterface
{

    /**
     * encrypt
     * 
     * Encrypt some variable
     * 
     * @param string $protectCode
     * @param mixed $toEncrypt
     */
    public function encrypt($protectCode, $toEncrypt);

    /**
     * decrypt
     *
     * Decrypt some variable
     *
     * @param string $protectCode
     * @param mixed $toDecrypt
     */
    public function decrypt($protectCode, $toDecrypt);

    /**
     * generateIv
     * 
     * Generates an IV used for encryption. It is important that
     * you use the same IV to encrypt if you are going to decrypt.
     */
    public function generateIv();
}
