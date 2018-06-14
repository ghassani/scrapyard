<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Templating;

class TemplateBlockHelper
{
    /**
     * getAllBlocks
     *
     * Extract all block names defined in a twig source file
     *
     * @param string $source
     * @return mixed
     */
    public static function getAllBlocks($source)
    {
        preg_match_all("/\{%\s+block\s+([a-zA-Z\_0-9]+)\s+%\}/", $source, $matches);
        return $matches[1];
    }
} 