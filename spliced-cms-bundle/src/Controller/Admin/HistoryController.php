<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/history")
 */
class HistoryController extends Controller
{

    /**
     * @Route("/add", name="spliced_cms_admin_history_add")
     */
    public function addHistoryAction(Request $request)
    {
        $historyManager = $this->get('spliced_cms.history_manager');
        $response = new JsonResponse(array());
        
        if (!$request->request->has('url')) {
            return $response->setData(array(
                'success' => false,
                'message' => 'URL is Required'
            ));
        }
        
        if (!$request->request->has('name')) {
            return $response->setData(array(
                'success' => false,
                'message' => 'Name is Required'
            ));
        }
        
        $history = $historyManager->getHistory();
        $lastHistory = current($history);
        
        // add history only if it is not the same as the last one
        if ($lastHistory['url'] != $request->request->get('url')) {
            $historyManager->addHistory(
                $request->request->get('url'),
                $request->request->get('name'),
                $request->request->get('time', time())
            );
        }
        
        return $response->setData(array(
            'success' => true,
            'data' => $this->get('spliced_cms.history_manager')->getHistory()
        ));
    }

    /**
     * @Route("/get", name="spliced_cms_admin_history_get")
     */
    public function getHistoryAction(Request $request)
    {
        return new JsonResponse(array(
            'success' => true,
            'data' => $this->get('spliced_cms.history_manager')->getHistory()
        ));
    }
}
