<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Salsa\Response;

use Spliced\Component\Salsa\Request\RequestInterface;

/**
 * ResponseInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface ResponseInterface{

    public function __construct(RequestInterface $request, $rawResponse);
    
    public function getResponse();

    public function isSuccess();
    
    public function isError();
    
    public function getError();
}