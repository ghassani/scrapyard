<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\CarrierApple\Request;


/**
 * CheckCarrier
 * 
 * Retrieves carrier information for an iPhone by MEID
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckCarrier extends Request
{
    /**
     * Constructor
     * 
     * @param string $imei
     */
     public function __construct($imei)
     {
         if(strlen($imei) != 15){
             throw new \InvalidArgumentException(sprintf('IMEI %s is Invalid', $imei));    
         }
        
         parent::__construct(array('imei' => $imei));
     }
     
    /**
     * {@inheritDoc}
     */
    public function getCallMethod()
    {
        return 'check-carrier';
    }
    
}