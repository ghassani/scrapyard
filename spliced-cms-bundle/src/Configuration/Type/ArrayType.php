<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Configuration\Type;

use Spliced\Bundle\ConfigurationBundle\Type\ArrayType as BaseType;

class ArrayType extends BaseType
{
    /**
     * {@inheritDoc}
     */
    public function getFormTemplatePath()
    {
        return 'SplicedCmsBundle:Admin/ConfigurationItem/type:array.html.twig';
    }
}