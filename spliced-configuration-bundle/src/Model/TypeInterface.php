<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\Model;

/**
 * Interface TypeInterface
 *
 * Represents a configuration value type to handle store
 * configuration values.
 *
 * @package Spliced\Bundle\ConfigurationBundle\Type
 */
interface TypeInterface
{
    /**
     * getName
     *
     * A name for the configuration field type
     *
     * @return string
     */
    public function getName();

    /**
     * getKey
     *
     * A unique key for the configuration field type
     *
     * @return string
     */
    public function getKey();

    /**
     * getDescription
     *
     * A description for the configuration field type
     *
     * @return string
     */
    public function getDescription();

    /**
     * buildForm
     *
     * Construct the form for editing the field value
     *
     * @param
     */
    public function buildForm($item, $builder);

    /**
     * getFormTemplatePath
     *
     * Return the path to the template for the type form layout.
     * If empty, the form will be rendered standard without any
     * customized formatting.
     *
     * @return string
     */
    public function getFormTemplatePath();
    
    /**
     * transformValueToDatabase
     *
     * Transform the value to be stored in the database
     *
     * @param $value
     *
     * @return mixed
     */
    public function transformValueToDatabase($value);
    
    /**
     * transformValueToParameter
     *
     * Transform the value when retrieved from the database
     *
     * @return mixed
     */
    public function transformValueToParameter($value);
    
    /**
     * transformValueToParameter
     *
     * Transform the value to be displayed to the user in a friendly/appropriate format
     *
     * @return mixed
     */
    public function transformValueToDisplay($value);
}