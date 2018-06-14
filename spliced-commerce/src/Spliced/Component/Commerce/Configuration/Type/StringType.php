<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Configuration\Type;

use Spliced\Component\Commerce\Model\ConfigurationInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * StringType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class StringType extends TextType
{
    /**
     * {@inheritDoc}
    */
    public function getName()
    {
        return 'string';
    }

}
