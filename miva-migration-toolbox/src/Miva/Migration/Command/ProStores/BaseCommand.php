<?php

namespace Miva\Migration\Command\ProStores;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Miva\Migration\Command\BaseCommand as DefaultBaseCommand;

/**
* BaseCommand
*
* 
*/
class BaseCommand extends DefaultBaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
    }

    /**
    * {@inheritDoc}
    */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

    }

    
    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
    }

    /**
     * getCustomerExportHeader
     * 
     * @access protected
     *
     * @return array
     */
    protected function getCustomerExportHeader()
    {
    	return array(
			'CustomerNo',
			'LastName',
			'FirstName',
			'Company',
			'Street',
			'Street2',
			'City',
			'State',
			'Zip',
			'Country',
			'Phone',
			'Phone2',
			'Phone3',
			'Fax',
			'Fax2',
			'Pager',
			'Email',
			'Email2',
			'Resale',
			'AuthAllowed',
			'Notify',
			'Since',
			'ExtText1',
			'ExtText2',
			'ExtText3',
			'ExtText4',
			'ExtText5',
			'ExtText6',
			'ExtText7',
			'ExtText8',
			'GroupName',
		);
    }

    /**
     * getProductExportHeader
     * 
     * @access protected
     *
     * @return array
     */
    protected function getProductExportHeader()
    {
        return array(
            'ProductNo',
            'Product',
            'SKU',
            'Oem',
            'Supplier',
            'ProductType',
            'Price',
            'PriceRetail',
            'Cost',
            'Taxable',
            'Surcharge',
            'SurchargeTitle',
            'Shipping',
            'ShippingExemptInd',
            'SaleExclude',
            'Quantity',
            'Unit',
            'Threshold',
            'Weight',
            'Length',
            'Height',
            'Width',
            'ContainerCode',
            'Brief',
            'Description',
            'Subscription',
            'Thumbnail',
            'Photo',
            'Template',
            'ListingTemplate',
            'Featured',
            'ExtText1',
            'ExtText2',
            'ExtText3',
            'ExtText4',
            'ExtText5',
            'ExtText6',
            'Active',
            'AuthReq',
            'Isbn',
            'Brand',
            'Mpn',
            'Upc',
            'Ean',
            'eBayProductId',
            'ProductReferenceId',
            'Condition',
            'Media',
            'NaturalSearchKeywords',
            'NaturalSearchDescription',
            'Category',
            'Keywords',
            'GoogleCategory',
            'GoogleAgeGroup',
            'GoogleGender',
            'GoogleColor',
            'GoogleSize',
            'Attribute1SharedName',
            'Attribute1Label',
            'Attribute1Values',
            'Attribute1DisplayType',
            'Attribute2SharedName',
            'Attribute2Label',
            'Attribute2Values',
            'Attribute2DisplayType',
            'Attribute3SharedName',
            'Attribute3Label',
            'Attribute3Values',
            'Attribute3DisplayType',
            'Attribute4SharedName',
            'Attribute4Label',
            'Attribute4Values',
            'Attribute4DisplayType',
            'Attribute5SharedName',
            'Attribute5Label',
            'Attribute5Values',
            'Attribute5DisplayType',
            'Attribute6SharedName',
            'Attribute6Label',
            'Attribute6Values',
            'Attribute6DisplayType',
            'Attribute7SharedName',
            'Attribute7Label',
            'Attribute7Values',
            'Attribute7DisplayType',
            'Attribute8SharedName',
            'Attribute8Label',
            'Attribute8Values',
            'Attribute8DisplayType',
            'Attribute9SharedName',
            'Attribute9Label',
            'Attribute9Values',
            'Attribute9DisplayType',
            'Attribute10SharedName',
            'Attribute10Label',
            'Attribute10Values',
            'Attribute10DisplayType',
        );
    }
}