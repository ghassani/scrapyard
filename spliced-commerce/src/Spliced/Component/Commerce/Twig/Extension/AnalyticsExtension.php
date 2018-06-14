<?php

namespace Spliced\Component\Commerce\Twig\Extension;

use Spliced\Component\Commerce\Analytics\AnalyticsManager;

class AnalyticsExtension extends \Twig_Extension
{
    /* @var AnalyticsManager */
    protected $analyticsManager;

    /**
     * Constructor
     * 
     * @param AnalyticsManager $analyticsManager
     */
    public function __construct(AnalyticsManager $analyticsManager)
    {
       $this->analyticsManager = $analyticsManager;
    }

    /**
     * getAnalyticsManager
     * 
     * @return AnalyticsManager
     */
    protected function getAnalyticsManager()
    {
        return $this->analyticsManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(

        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'commerce_render_analytics' => new \Twig_Function_Method($this, 'renderAnalyticsHtml'),
        );
    }

    /**
     * renderAnalyticsHtml4
     * 
     * @return string - html
     */
    public function renderAnalyticsHtml()
    {
        $html = '';
        foreach($this->getAnalyticsManager()->getSubscribers() as $subscriber){
            $html .= $subscriber->renderTrackerHtml();
        }
        return $html;
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_analytics';
    }

}
