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

use Symfony\Bundle\FrameworkBundle\Templating\TemplateNameParser as BaseTemplateNameParser;
use Symfony\Component\Templating\TemplateReference;

class TemplateNameParser extends BaseTemplateNameParser
{
	/**
	 * {@inheritdoc}
	 */
	public function parse($name)
	{
		if (preg_match('/^@/', $name)) {
			return new TemplateReference($name, 'twig');
		}
		
		return parent::parse($name);
	}
}