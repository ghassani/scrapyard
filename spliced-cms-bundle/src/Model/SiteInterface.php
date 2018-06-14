<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Model;

interface SiteInterface
{
    /**
     * toArray
     *
     * Turn the object into an array, recursively for relations
     *
     * @return array
     */
    public function toArray();

    /**
     * toJson
     *
     * Turn the object into a valid JSON string
     *
     * Ideally, encode toArray()
     *
     * @return string
     */
    public function toJson();
    
}