<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Spliced\Component\Commerce\Form\Type\Admin\LoginFormType as BaseLoginFormType;

/**
 * LoginFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class LoginFormType extends BaseLoginFormType
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return '';
    }
}
