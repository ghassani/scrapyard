<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Salsa\Utility;

use Spliced\Component\Salsa\Client;

/**
 * ActionWrapper
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ActionWrapper{
    
    protected $client;
    
    /**
     * 
     */
    public function __construct(Client $client){
        $this->client = $client;
    }
    
    /**
     * 
     */
    public function loadAction($actionKey, $withContent = true, $withTargets = true){
        $action    = $this->loadObject('action', $actionKey);
        
        if($withContent === true){
            $action['content'] = $this->loadActionContent($actionKey, true);
        }
        
        if($withTargets){
            $actionTargets     = $this->loadObjects('action_target', 'action_KEY='.$actionKey);
        }
    }
    
    /**
     * 
     */
    public function loadActionContent($actionKey, $withDetail = true){
        $content     = $this->loadObjects('action_content', 'action_KEY='.$actionKey, true);
        if($withDetail === true){
            $content['detail'] = $this->loadActionContentDetail($content['key']);
        }
        
        return $content;
    }
    
    /**
     *
     */
    public function loadActionContentDetail($actionContentKey){
        return $this->loadObjects('action_content_detail', 'action_content_KEY='.$actionContentKey, true);
    }
    
    /**
     *
     */
    public function loadActionTargets($actionKey, $withRecipients = true){
        $return = $this->loadObjects('action_target', 'action_KEY='.$actionKey, false);
        return $return;
    }
    
    /**
     * loadObject
     */
    public function loadObject($object,$key){
        $response = $this->client->processRequest(
            $this->client->createRequest('GetObject')
             ->setObject($object)
             ->setKey($key)
        );
    
        if($response->isError()){
            throw new \Exception('Error Retrieving Object from Salsa: '.$response->getMessage());
        }
        return $response->getObject();
    }
    
    /**
     * loadObjects
     */
    public function loadObjects($object, $condition, $returnFirstResultOnly = false){
        $response = $this->client->processRequest(
            $this->client->createRequest('GetObjects')
             ->setObject($object)
             ->setParameter('condition', $condition)
        );
    
        if( $response->isError() ){
            throw new \Exception('Error Retrieving Objects from Salsa: '.$response->getMessage());
        }
    
        $results = $response->getObjects();
    
        if( true === $returnFirstResultOnly ){
            return end($results);
        }
    
        return $results;
    }
    
    
}