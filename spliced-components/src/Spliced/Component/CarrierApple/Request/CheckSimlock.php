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
 * CheckSimlock
 * 
 * Retrieves carrier information for an iPhone by MEID
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckSimlock extends CheckCarrier
{
    /**
     * {@inheritDoc}
     */
    public function getCallMethod()
    {
        return 'check-simlock';
    }
    
}