<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Utility\Tests\Utility;

use Spliced\Component\Utility\EmailValidator;

/**
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class EmailValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test a Valid Email Address
     */
    public function testValidEmail(){
        
        $validator = new EmailValidator(true);
        
        $testAddress = 'ghassani@gmail.com';
        
        print sprintf('Testing Valid Address: %s',$testAddress)."\n";
        
        $result = $validator->validate($testAddress, 'ghassan.idriss.us', 'ghassani@gmail.com');
        
        $this->assertEquals(true, $result, $validator->getLastError());        
    }
    
    /**
     * Test an Invalid Syntax Email
     */
    public function testInvalidSyntaxEmail(){
    
        $validator = new EmailValidator(true);
    
        $testAddress = 'ghassani@gmailcom';
    
        print sprintf('Testing Invalid Syntax: %s',$testAddress)."\n";
    
        $result = $validator->validate($testAddress, 'ghassan.idriss.us', 'ghassani@gmail.com');
    
        $this->assertEquals(false, $result, $validator->getLastError());
    }
    
    
    /**
     * Test an Invalid Email (non-deliverable)
     */
    public function testInvalidEmail(){
    
        $validator = new EmailValidator(true);
    
        $testAddress = 'ghassani45fgsdfsdfsdf6@gmail.com';    
        print sprintf('Testing Invalid Address: %s',$testAddress)."\n";
    
        $result = $validator->validate($testAddress, 'ghassan.idriss.us', 'ghassani@gmail.com');
        
        print $validator->getLastError();
        
        $this->assertEquals(false, $result, $validator->getLastError());
    }
    
}