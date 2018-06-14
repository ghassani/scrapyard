<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Repository;

/**
 * RoutetRepositoryInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface RouteRepositoryInterface
{
    /**
     * matchRoute
     *
     * @param string $requestPath
     */
    public function matchRoute($requestPath);
}