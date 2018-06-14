<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Editor\Extension;

use Spliced\Bundle\CmsBundle\Model\EditorExtensionInterface;

class ContentBlockExtension implements EditorExtensionInterface

{
    const EXTENSION_NAME = 'content_block';

    public function getName()
    {
        return static::EXTENSION_NAME;
    }

    public function getButtons()
    {
    }
}