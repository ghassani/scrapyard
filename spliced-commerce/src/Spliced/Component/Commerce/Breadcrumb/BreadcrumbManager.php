<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Breadcrumb;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * BreadcrumbManager
 *
 * Handles the mangment of Breadcrumb items to be displayed 
 * in a template. Use this class to manage the breadcrumbs that
 * will get displayed at render time.
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class BreadcrumbManager
{
    /**
     * @var $breadcrumbs - Breadcrumb ArrayCollection
     */
    protected $breadcrumbs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->breadcrumbs = new ArrayCollection();
    }

    /**
     * createBreadcrumb
     * 
     * @param string $ancthor
     * @param string $title
     * @param string $href
     * @param int    $position
     * @param bool $add - Add the crumb or return the crumb, defaults to return the crumb (false)
     *
     * @return Breadcrumb
     */
    public function createBreadcrumb($anchor, $title, $href, $position = null, $add = false)
    {
        $_position = is_null($position) ? $this->breadcrumbs->count() : (int) $position;

        if ($add === true) {
            $this->addBreadcrumb(new Breadcrumb($anchor, $title, $href, $_position));
            return $this;
        } 
        return new Breadcrumb($anchor, $title, $href, $_position);
    }
    
    
    /**
     * addBreadcrumb
     * @param string $ancthor
     * @param string $title
     * @param string $href
     * @param int    $position
     *
     * @return Breadcrumb
     */
    public function addBreadcrumb(Breadcrumb $breadcrumb)
    {
        $this->breadcrumbs->add($breadcrumb);
        return $this;
    }
     
    /**
     * getBreadcrumbs
     *
     * @return ArrayCollection
     */
    public function getBreadcrumbs()
    {
        return $this->breadcrumbs;
    }
    
    /**
     * setBreadcrumbs
     *
     * @param ArrayCollection $breadcrumbs
     *
     * @return BreadcrumbManager
     */
    public function setBreadcrumbs(ArrayCollection $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
        return $this;
    }
    
    /**
     * removeBreadcrumb
     *
     * @param ArrayCollection $breadcrumbs
     *
     * @return BreadcrumbManager
     */
    public function removeBreadcrumb(Breadcrumb $breadcrumb)
    {
        $this->breadcrumbs->removeElement($breadcrumb);
        return $this;
    }
}
