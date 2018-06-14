<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Manager;

use Symfony\Component\HttpFoundation\Session\Session;

class HistoryManager
{
    const SESSION_TAG = 'spliced.cms.history.admin';
    const MAX_HISTORY = 30;

    /**
     * @var Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * @var array
     */
    protected $history = array();

    /**
     * Constructor
     *
     * @access public
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->history = $session->get(static::SESSION_TAG, array());
    }

    /**
     * @return Symfony\Component\HttpFoundation\Session\Session|Session
     */
    protected function getSession()
    {
        return $this->session;
    }

    /**
     * @return array
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @param array $history
     * @return $this
     */
    public function setHistory(array $history)
    {
        $this->getSession()->set(static::SESSION_TAG, $history);
        $this->history = array_slice($history, 0, static::MAX_HISTORY, false);
        return $this;
    }

    /**
     * clear
     *
     * @return $this
     */
    public function clear()
    {
        return $this->setHistory(array());
    }
    
    /**
     * @param $url
     * @param $name
     * @param null $time
     * @return $this
     */
    public function addHistory($url, $name, $time = null)
    {
        $history = $this->getHistory();
        array_unshift($history, array(
            'url' => $url,
            'name' => $name,
            'time' => $time ? $time instanceof \DateTime ? $time->getTimestamp() : $time : time()
        ));
         return $this->setHistory($history);
    }
}