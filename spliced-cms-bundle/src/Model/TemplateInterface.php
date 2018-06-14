<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Model;

interface TemplateInterface
{
    /**
     * getActiveVersion
     *
     * Get the current content active version
     *
     * @return TemplateVersionInterface
     */
    public function getActiveVersion();

    /**
     * setActiveVersion
     *
     * Set the current content active version.
     *
     * Optionally used to store a work in progress as well as a live version
     *
     * If utilizing, this would be the live version
     *
     * @param TemplateVersionInterface $version
     * @return TemplateInterface
     */
    public function setActiveVersion(TemplateVersionInterface $version);

    /**
     * getVersion
     *
     * Get the current content version
     *
     * @return TemplateVersionInterface
     */
    public function getVersion();

    /**
     * setVersion
     *
     * Set the current content version
     *
     * If using activeVersion, this would be the draft.
     *
     * @param TemplateVersionInterface $version
     * @return TemplateInterface
     */
    public function setVersion(TemplateVersionInterface $version);

    /**
     * The unique filename of the template
     * @return string
     */
    public function getFilename();

    /**
     * toArray
     *
     * Turn the object into an array, recursively for relations
     *
     * @return array
     */
    public function toArray();
    
    /**
     * toJson
     *
     * Turn the object into a valid JSON string
     *
     * Ideally, encode toArray()
     *
     * @return string
     */
    public function toJson();
}