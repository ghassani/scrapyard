<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Logger;

use Symfony\Bridge\Monolog\Logger as BaseLogger;
use Monolog\Handler\StreamHandler;

/**
 * Logger
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Logger extends BaseLogger
{
    const VISITOR = 201;
    const JAVASCRIPT_NOTICE = 320;
    const JAVASCRIPT_ERROR = 325;
    const PAGE_NOT_FOUND = 350;
    const EXCEPTION = 700;
    
    protected static $levels = array(
        100 => 'DEBUG',
        200 => 'INFO',
        201 => 'VISITOR',
        250 => 'NOTICE',
        300 => 'WARNING',
        320 => 'JAVASCRIPT_NOTICE',
        325 => 'JAVASCRIPT_ERROR',
        350 => 'PAGE_NOT_FOUND',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
        700 => 'EXCEPTION',
    );
    /**
     * Construct
     * 
     * @param \AppKernel $kernel
     */
     public function __construct(\AppKernel $kernel)
     {
         $logPath = sprintf('%s/logs/commerce.%s.log', $kernel->getRootDir(), $kernel->getEnvironment());
        
         parent::__construct('commerce', array(), array());
        
        $this->pushHandler(new StreamHandler($logPath, 100));
     }
     
    public function javascriptError($message, array $context = array())
    {
        return parent::addRecord(static::JAVASCRIPT_ERROR, $message, $context);
    }
    
    public function javascriptNotice($message, array $context = array())
    {
        return parent::addRecord(static::JAVASCRIPT_NOTICE, $message, $context);
    }
    
    public function visitor($message, array $context = array())
    {
        return parent::addRecord(static::VISITOR, $message, $context);
    }
     
    public function exception($message, array $context = array())
    {
        return parent::addRecord(static::EXCEPTION, $message, $context);
    }
    
    public function pageNotFound($message, array $context = array())
    {
        return parent::addRecord(static::PAGE_NOT_FOUND, $message, $context);
    }
}