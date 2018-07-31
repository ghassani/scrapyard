<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Salsa;

use Spliced\Component\Salsa\Request\RequestInterface;
use Spliced\Component\Salsa\Response\ResponseInterface;
use Spliced\Component\Salsa\Request\Authenticate;

/**
 * Client
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Client{
    
    #const API_URL_LIVE = 'https://wfc2.salsalabs.com';
    const API_URL_LIVE = 'https://org2.salsalabs.com';
    const API_URL_TEST = 'https://sandbox.salsalabs.com';
    const COOKIE_PATH = '/tmp/salsa_auth';
    
    /**
     * string $email
     */
    private $email;
    
    /**
     * string $password
     */
    private $password;
    
    /**
     * bool $sandbox
     */
    private $sandbox;

    protected $isAuthenticating;
    
    protected $session = null;
    
    
    /**
     * $callStack
     */
    protected $callStack = array('request' => array(),'response' => array());
    
    /**
     * __construct
     * @param string $email
     * @param string $password
     * @param string $sandbox
     */
    public function __construct($email, $password, $sandbox = true){
        $this->email = $email;
        $this->password = $password;
        $this->sandbox = $sandbox;
    }
    
    /**
     * __destruct
     */
    public function __destruct(){
        if(isset($this->session)){
            curl_close($this->session);
        }
    }
    
    /**
     * authenticate
     */
    public function authenticate(){
        
        $this->isAuthenticating = true; 
        
        $this->session = curl_init();
            
        curl_setopt($this->session, CURLOPT_POST, 1);
        curl_setopt($this->session, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->session, CURLOPT_TIMEOUT, 100);
        curl_setopt($this->session, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($this->session, CURLOPT_COOKIEFILE, self::COOKIE_PATH);
        curl_setopt($this->session, CURLOPT_COOKIEJAR, self::COOKIE_PATH);
        
        // attempt to create one
        $request = $this->createRequest('Authenticate');
        
        $request->setEmail($this->getEmail())
          ->setPassword($this->getPassword());
        
        $response = $this->processRequest($request);

        $this->isAuthenticating = false;
        
        if($this->isSandbox()){
            array_push($this->responseStack, $response);
        }
        
        if($response->isSuccess()){
            return true;
        }
        return $response;
    }
    
    /**
     * getEmail
     *
     * @return string
     */
    public function getEmail(){
        return $this->email;
    }
    
    /**
     * getPassword
     * 
     * @return string
     */
    public function getPassword(){
        return $this->password;
    }
    
    /**
     * isSandbox
     * 
     * @return bool
     */
    public function isSandbox(){
        return $this->sandbox;
    }
    
    /**
     * getUrl
     * 
     * @return string
     */
    public function getUrl($requestPath = null){
        $url = self::API_URL_LIVE;
        if($this->isSandbox()){
            $url = self::API_URL_TEST;
        }
        
        return is_null($requestPath) ? $url : $url.'/'.$requestPath;
    }
            
    /**
     * processRequest
     * 
     * @return ResponseInterface
     */
    public function processRequest(RequestInterface $request){
        
        if(!$request->isValid()){
            throw new Exception\InvalidRequestTypeException('Request is invalid or missing required parameters');
        }
        
        if(true !== $this->isAuthenticating && !$this->isAuthenticated()){
            $authenticated = $this->authenticate();
            if(is_object($authenticated)){
                return $authenticated;
            }
        }

        curl_setopt($this->session, CURLOPT_URL, $this->getUrl($request->getRequestPath()));
        curl_setopt($this->session, CURLOPT_POSTFIELDS, http_build_query($request->getParameters()));
        
        $this->addToRequestCallStack($request);

        $response = $this->createResponse($request, curl_exec($this->session));
        
        $this->addToResponseCallStack($response);
        
        return $response;
    }
    
    /**
     * createRequest
     * 
     * @return RequestInterface
     */
    public function createRequest($requestName){
        $className = '\\Spliced\\Component\\Salsa\\Request\\'.$requestName;
        if(class_exists($className)){
            return new $className();
        }
        throw new Exception\InvalidRequestTypeException(sprintf('Request %s is not a valid request type', $requestName));
    }
    
    /**
     * createResponse
     * 
     * @param RequestInterface $request
     * @param string $rawResponse
     */
    protected function createResponse(RequestInterface $request, $rawResponse){
        $responseClassName = explode('\\',get_class($request));
        $responseClass = '\\Spliced\\Component\\Salsa\\Response\\'.end($responseClassName); 
        return class_exists($responseClass) ? new $responseClass($request, $rawResponse) : new \Spliced\Component\Salsa\Response\Generic($request, $rawResponse);
    }
    
    public function isAuthenticated(){
        return !is_null($this->session);
    }
    
    /**
     * getCallStack
     * 
     * @param string $event - request or response if desired, or all if null
     * @return array
     */
    public function getCallStack($event = null){
        $event = strtolower($event);
        if(!is_null($event) && !in_array($event,array('request','response'))){
            return $this->callStack[$event];
        }
        return $this->callStack;
    }
    
    /**
     * addToResponseCallStack
     * 
     * @param ResponseInterface $response
     * 
     * @return Client
     */
    public function addToResponseCallStack(ResponseInterface $response){
        $this->callStack['response'][] = $response;
        return $this;
    }
    
    /**
     * addToRequestCallStack
     * 
     * @param RequestInterface $request
     * 
     * @return Client
     */
    public function addToRequestCallStack(RequestInterface $request){
        $this->callStack['request'][] = $request;
        return $this;
    }
}