<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
 
namespace Spliced\Component\Salsa\Request;

/**
 * RequestInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface RequestInterface{

    public function getRequestPath();
    
    public function getParameters();
    
    public function setParameter($name, $value);
    
    public function getParameter($name);
    
    public function isValid();
}