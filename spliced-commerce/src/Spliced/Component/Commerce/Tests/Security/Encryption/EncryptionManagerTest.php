<?php

namespace Spliced\Component\Commerce\Tests\Security\Encryption;

use Spliced\Component\Commerce\Security\Encryption\EncryptionManager;

class EncryptionManagerTest extends \PHPUnit_Framework_TestCase
{

    public function testEncryption()
    {
        $encryptionManager = new EncryptionManager();

        $hash = md5(rand(100,1000));
        $protectCode = substr($hash,strlen($hash)-10);
        $iv = $encryptionManager->generateIv();
        print('IV: '.$iv.PHP_EOL);
        $textToEncrypt = 'SplicedCommerceBundle';
        $iv = '1a345i6y912r45b78bc234x6as912e45';
        print(PHP_EOL.'Hash: '.$hash.PHP_EOL);
        print('Protect Code: '.$protectCode.PHP_EOL);
        print('IV: '.$iv.PHP_EOL);
        print('Text To Encrypt: '.$textToEncrypt.PHP_EOL);

        $encryptedText = $encryptionManager->encrypt($protectCode, $iv, $textToEncrypt);

        $this->assertNotEquals($textToEncrypt, $encryptedText);

        #print('Encrypted Text: '.$encryptedText.PHP_EOL);

        $decryptedText = $encryptionManager->decrypt($protectCode, $iv, $encryptedText);
        #print('Decrypted Text: '.$decryptedText.PHP_EOL);
        $this->assertEquals($textToEncrypt, $decryptedText);

        $decryptedText = $encryptionManager->decrypt('asdasd', $iv, $encryptedText);

        $this->assertNotEquals($textToEncrypt, $decryptedText);
    }
}
