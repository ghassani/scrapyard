<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

class Event extends  BaseEvent
{

    const CONFIGURATION_SAVE   = 'spliced_configuration.save';
    const CONFIGURATION_UPDATE = 'spliced_configuration.update';
    const CONFIGURATION_DELETE = 'spliced_configuration.delete';
    const CONFIGURATION_LOAD   = 'spliced_configuration.load';

}