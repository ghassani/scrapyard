<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Salsa\Request;

/**
 * Authenticate
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Authenticate extends Base{
    
    protected $requestPath = 'api/authenticate.sjs';
    
    protected $requestParameters = array(
        'json' => '',
        'email' => null,
        'password' => null
    );
    
    /**
     * isValid
     * 
     * @return bool
     */
    public function isValid(){
        return $this->getEmail() && $this->getPassword() ? true : false;
    }
    
    /**
     * getEmail
     *
     * @return string
     */
    public function getEmail(){
        return $this->getParameter('email');
    }
    
    /**
     * setEmail
     * 
     * @param string $value
     */
    public function setEmail($value){
        return $this->setParameter('email',$value);
    }
    
    /**
     * getPassword
     * 
     * @return string
     */
    public function getPassword(){
        return $this->getParameter('password');
    }
    /**
     * setPassword
     *
     * @param string $value
     */
    public function setPassword($value){
        return $this->setParameter('password',$value);
    }
}